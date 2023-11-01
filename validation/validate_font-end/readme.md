# add file vào Layout Default

```php
    <script src="js/jquery/jquery-3.6.0.min.js"></script>
    <script src="js/jquery/jquery-validation-1.19.4/dist/jquery.validate.js"></script>
```

# dùng validate

## dùng validate bắt qua name

```js
$("id_form").validate({
  rules: {
    voucher_number: {
      required: true,
    },
    voucher_date: {
      required: true,
    },
    accounting_date: {
      required: true,
    },
  },
  submitHandler: function (form) {
    form.submit();
  },
});
```

## dùng validate qua class

```js
$.validator.addClassRules({
  "select-part-no": {
    required: true,
  },
  "input-tk_co": {
    maxlength: 255,
  },
  "input-tk_no": {
    maxlength: 255,
  },
  "input-unit": {
    required: true,
  },
  "input-quatity": {
    required: true,
  },
});
```

có thể gộp cả 2 lại như sau

```js
const input_inventory_pur = {
  validateDetail: function () {
    $.validator.addClassRules({
      "select-part-no": {
        required: true,
      },
      "input-tk_co": {
        maxlength: 255,
      },
      "input-tk_no": {
        maxlength: 255,
      },
      "input-unit": {
        required: true,
      },
      "input-quatity": {
        required: true,
      },
    });
  },
  validateForm: function () {
    $("#inventory_pur").validate({
      ignore: ":focus",
      rules: {
        voucher_number: {
          required: true,
        },
        voucher_date: {
          required: true,
        },
        accounting_date: {
          required: true,
        },
      },
      submitHandler: function (form) {
        let count_detail = $(".row-data").length;
        if (count_detail < 1) {
          $(".notification").html(
            '<div class="alert alert-danger alert-dismissible fade show" onclick="this.classList.add(\'hidden\')">' +
              '<?= __("Please add Detail for this Import Inventory!") ?>' +
              "</div>"
          );
          $("html, body").animate(
            { scrollTop: $(".form-detail").offset().top },
            "fast"
          );
        } else {
          $("#viewLoading").modal("show");
          form.submit();
        }
      },
    });
  },
};

// sử dụng
input_inventory_pur.validateForm();
input_inventory_pur.validateDetail();
```

# Thêm mới validate

- cú pháp

```js
$.validator.addMethod(name, method, message);
```

- ví dụ

```js
$.validator.addMethod(
  "exceedTrackingDeliveryDate",
  function (value, element, params) {
    let row = $(element).data("row");
    let tracking_request_of_delivery = $(
      "#input-tracking_request_of_delivery-" + row
    ).val();

    return value == tracking_request_of_delivery;
  },
  "Delivery Date must be equal to greatest tracking plan date!"
);
```

sau đó muốn dùng thì chỉ cần `exceedTrackingDeliveryDate : true` là xong

# set lại class validate

```js
$(document).ready(function () {
  $.validator.setDefaults({
    highlight: function (element) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },
    errorElement: "div",
    errorClass: "invalid-feedback",
    errorPlacement: function (error, element) {
      if (element.parent().length) {
        element.parent().append(error);
      } else {
        error.insertAfter(element);
      }
    },
  });
});
```
