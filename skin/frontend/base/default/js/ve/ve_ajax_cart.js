function initAjaxifyEvents() {

    if (typeof productAddToCartForm != 'undefined') {
        productAddToCartForm.submit = function () {
            var _this = this;
            if(this.form.id === 'product_addtocart_form_from_popup') {
                _this = productAddToCartFormOld;
            }
            if (_this.validator && _this.validator.validate()) {
                popupView(_this, true);
            }
            return false;
        }

        productAddToCartForm.form.onsubmit = function () {
            productAddToCartForm.submit();
            return false;
        };
    }

    $$('a[href*="/checkout/cart/delete/"]').each(function (e) {
        $(e).observe('click', function (event) {
            popupView($(e).readAttribute('href'), true, true);
            Event.stop(event);
        });
    });
}
document.observe("dom:loaded", function () {
    initAjaxifyEvents();
});