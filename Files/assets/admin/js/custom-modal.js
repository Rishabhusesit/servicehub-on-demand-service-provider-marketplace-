"use strict";
// Setup modal values
let customModal = $("#customModal");
let form = customModal.find("form");
const action = form[0] ? form[0].action : null;

$(document).on("click", ".customModalBtn", function () {
  let data = $(this).data();
  let resource = data.resource ?? null;

  $(form).trigger("reset");
  customModal.find(".image-upload-preview ").css("background-image", `url('')`);

  if ($(form).find("textarea").length) {
    $(form).find("textarea").text("");
  }

  form[0].action = `${action}`;
  customModal.find(".status").empty();

  customModal.find(".modal-title").text(`${data.modal_title}`);

  if (resource) {
    form[0].action = `${action}/${resource.id}`;
    // If form has image

    if (resource.image_with_path) {
      customModal
        .find(".image-upload-preview")
        .css("background-image", `url(${resource.image_with_path})`);
    }

    if (data.has_status) {
      customModal.find(".status").html(`
				<div class="form-group">
					<label class="font-weight-bold">Status</label>
					<input type="checkbox" data-width="100%" data-height="50" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="Enable" data-off="Disable" name="status">
				</div>
			`);

      customModal.find("[name=status]").bootstrapToggle();
    }

    let fields = customModal.find("input, select, textarea");
    let fieldName;

    fields.each(function (index, element) {
      fieldName = element.name;

      if ($(element).hasClass("colorPicker")) {
        $(element).spectrum({
          color: resource.color_code,
        });
      }

      if ($(element).hasClass("profilePicustompload")) {
        $(element).removeAttr("required");
      }
      // If input name is an array
      if (fieldName.substring(fieldName.length - 2) == "[]") {
        fieldName = fieldName.substring(0, fieldName.length - 2);
      }

      if (fieldName != "_token" && resource[fieldName]) {
        if (element.tagName == "TEXTAREA") {
          if ($(element).hasClass("nicEdit")) {
            $(".nicEdit-main").html(resource[fieldName]);
          } else {
            $(`[name='${fieldName}']`).text(resource[fieldName]);
          }
        } else if ($(element).data("toggle") == "toggle") {
          if (resource[fieldName] != 0) {
            $(element).bootstrapToggle("on");
          } else {
            $(element).bootstrapToggle("off");
          }
        } else if (element.type == "file") {
        } else if (element.tagName == "SELECT") {
          $(`[name='${element.name}']`)
            .val(resource[fieldName])
            .trigger("change");
        } else {
          $(`[name='${element.name}']`).val(
            $.isNumeric(resource[fieldName])
              ? resource[fieldName] * 1
              : resource[fieldName]
          );
        }
      }
    });
  } else {
    console.log(300);
  }
  customModal.modal("show");
});
