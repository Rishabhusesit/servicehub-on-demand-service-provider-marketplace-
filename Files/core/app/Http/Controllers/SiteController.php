<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller {
    public function index() {
        $reference = isset($_GET['reference']) ? $_GET['reference'] : null;
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle   = 'Home';
        $sections    = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = (isset($seoContents->image) && $seoContents->image) ? getImage(getFilePath('seo') . '/' . $seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function pages($slug) {
        $page        = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle   = $page->name;
        $sections    = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage    = (isset($seoContents->image) && $seoContents->image) ? getImage(getFilePath('seo') . '/' . $seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function contact() {
        $pageTitle   = "Contact Us";
        $user        = Auth::check() ? Auth::user() : Auth('provider')->user();
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = (isset($seoContents->image) && $seoContents->image) ? getImage(getFilePath('seo') . '/' . $seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }

    public function contactSubmit(Request $request) {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = auth()->id() ?? 0;
        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title     = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                    = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message           = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug) {
        $policy      = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle   = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage    = (isset($seoContents->image) && $seoContents->image) ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();
        if (!$language) {
            $lang = 'en';
        }

        session()->put('lang', $lang);
        return back();
    }

    public function blogs() {
        $pageTitle = 'Blogs';
        $blogs     = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate());
        $sections  = Page::where('tempname', activeTemplate())->where('slug', 'blog')->first();
        return view('Template::blog', compact('pageTitle', 'blogs', 'sections'));
    }

    public function blogDetails($slug) {
        $blog        = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $recentBlogs = Frontend::where('slug', '!=', $slug)->where('data_keys', 'blog.element')->latest()->limit(3)->get();
        $pageTitle   = 'Blog Detail';
        $seoContents = $blog->seo_content;
        $seoImage    = (isset($seoContents->image) && $seoContents->image) ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'recentBlogs'));
    }

    public function cookieAccept() {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy() {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null) {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance() {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function subscribe(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $subscriber        = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        return response()->json(['success' => true, 'message' => 'Thank you for subscribing! We\'ll keep you updated.']);
    }

    public function joinAs() {

        $pageTitle = 'Join As';
        return view('Template::join', compact('pageTitle'));
    }

    public function allService() {
        $pageTitle  = "All Service";
        $categories = Category::active()
            ->withWhereHas('services', function ($query) {
                $query->active();
            })->searchable(['services:name', 'name'])->get();
        $activeServiceSlug = request()->get('activeService');
        return view('Template::all_service', compact('pageTitle', 'categories', 'activeServiceSlug'));
    }

    public function serviceDetails($slug) {
        $pageTitle = "Service Details";
        $service   = Service::where('slug', $slug)->active()->withWhereHas('category', function ($q) {
            $q->active();
        })->firstOrFail();

        if (!$service) {
            $service = ServiceOption::where('slug', $slug)->active()->with(['service' => function ($q) {
                $q->active();
            }])->firstOrFail();
        }

        $this->addToRecentlyViewed($service->id);
        return view('Template::service_details', compact('pageTitle', 'service'));
    }

    protected function addToRecentlyViewed($serviceId) {
        $recentlyViewed = session()->get('recently_viewed_services', []);
        $recentlyViewed = array_unique(array_merge([$serviceId], $recentlyViewed));
        session()->put('recently_viewed_services', $recentlyViewed);
    }

    public function serviceByCategory($id) {
        $category = Category::where('id', $id)->with('services', function ($q) {
            return $q->active()->limit(4);
        })->first();

        return response()->json(['category' => $category]);
    }

    public function fetchOptionDetails(Request $request) {
        $optionId = $request->input('optionId');
        $service  = ServiceOption::where('id', $optionId)->active()->first();
        if (!$service) {
            return response()->json(['error' => 'Service not found.'], 404);
        }
        $options = ServiceOption::where('parent_id', $optionId)->active()->get();
        if ($options->isEmpty()) {
            $options = ServiceOption::where('service_id', $service->service_id)->where('parent_id', $service->parent_id)->active()->get();
        }
        $content = view('Template::service_options', compact('service', 'options'))->render();
        return response()->json([
            'content' => $content,
            'service' => $service,
        ]);
    }

    public function fetchParentOptionDetails(Request $request) {

        $optionId = $request->input('optionId');
        $service  = ServiceOption::where('id', $optionId)->active()->first();
        if (!$service) {
            return response()->json(['error' => 'Service option not found.'], 404);
        }

        $parentOption = ServiceOption::active()->where('id', $optionId)->first();
        if (!$parentOption) {
            return response()->json(['error' => 'Service not found.'], 404);
        }

        $parentService = ServiceOption::active()->where('id', $parentOption->parent_id)->first();
        if (!$parentService) {
            $parentService = Service::where('id', $parentOption->service_id)->active()->first();
        }

        if (!$parentService) {
            return response()->json(['error' => 'Service not found.'], 404);
        }

        $parentOptions = ServiceOption::where('parent_id', $parentOption->parent_id)->where('service_id', $service->service_id)->active()->get();
        $content       = view('Template::parent_service_options', compact('service', 'parentService', 'parentOptions'))->render();
        return response()->json([
            'content' => $content,
            'service' => $service,
        ]);

    }

    public function pusher($socketId, $channelName) {
        $pusherSecret = gs('pusher_app_secret');
        $str          = $socketId . ":" . $channelName;
        $hash         = hash_hmac('sha256', $str, $pusherSecret);
        return response()->json([
            'auth' => gs('pusher_app_key') . ":" . $hash,
        ]);
    }
}
