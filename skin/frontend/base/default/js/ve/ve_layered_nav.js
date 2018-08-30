// checking if IE: this variable will be understood by IE: isIE = !false
isIE = /*@cc_on!@*/false;
Control.Slider.prototype.setDisabled = function ()
{
    this.disabled = true;
    if (!isIE)
    {
        this.track.parentNode.className = this.track.parentNode.className + ' disabled';
    }
};
function ve_layered_hide_products()
{
    
    var items = $('ve_filters_list').select('a', 'input');
    n = items.length;
    for (i = 0; i < n; ++i) {
        items[i].addClassName('ve_layered_disabled');
    }

    if (typeof (ve_slider) != 'undefined')
        ve_slider.setDisabled();
    showPopupLoader();
    return;
    var divs = $$('div.ve_loading_filters');
    for (var i = 0; i < divs.length; ++i)
        divs[i].show();
}

function ve_layered_show_products(transport)
{
    var resp = {};
    if (transport && transport.responseText) {
        try {
            resp = eval('(' + transport.responseText + ')');
        }
        catch (e) {
            resp = {};
        }
    }

    if (resp.products) {

        var ajaxUrl = $('ve_layered_ajax').value;
        if ($('ve_layered_container') == undefined) {

            var c = $$('.col-main')[0]; // alert(c.hasChildNodes());
            if (c.hasChildNodes()) {
                while (c.childNodes.length > 2) {
                    c.removeChild(c.lastChild);
                }
            }

            var div = document.createElement('div');
            div.setAttribute('id', 've_layered_container');
            $$('.col-main')[0].appendChild(div);
        }

        var el = $('ve_layered_container');
        el.update(resp.products.gsub(ajaxUrl, $('ve_layered_url').value));
        catalog_toolbar_init();
        $('catalog-filters').update(
                resp.layer.gsub(
                        ajaxUrl,
                        $('ve_layered_url').value
                        )
                );
        $('ve_layered_ajax').value = ajaxUrl;
    }
    closePopupLoader();
    var items = $('ve_filters_list').select('a', 'input');
    n = items.length;
    for (i = 0; i < n; ++i) {
        items[i].removeClassName('ve_layered_disabled');
    }
    if (typeof (ve_slider) != 'undefined')
        ve_slider.setEnabled();
}

function ve_layered_add_params(k, v, isSingleVal)
{
    var el = $('ve_layered_params');
    var params = el.value.parseQuery();
    var strVal = params[k];
    if (typeof strVal == 'undefined' || !strVal.length) {
        params[k] = v;
    }
    else if ('clear' == v) {
        params[k] = 'clear';
    }
    else {
        if (k == 'price')
            var values = strVal.split(',');
        else
            var values = strVal.split('-');
        if (-1 == values.indexOf(v)) {
            if (isSingleVal)
                values = [v];
            else
                values.push(v);
        }
        else {
            values = values.without(v);
        }

        params[k] = values.join('-');
    }

    el.value = Object.toQueryString(params).gsub('%2B', '+');
}



function ve_layered_make_request()
{
    ve_layered_hide_products();
    var params = $('ve_layered_params').value.parseQuery();
    if (!params['dir'])
    {
        $('ve_layered_params').value += '&dir=' + 'desc';
    }

    new Ajax.Request(
            $('ve_layered_ajax').value + '?' + $('ve_layered_params').value,
            {
                method: 'get',
                onSuccess: ve_layered_show_products
            }
    );
}

function ve_layered_update_links(evt, className, isSingleVal)
{
    var link = Event.findElement(evt, 'A'),
            sel = className + '-selected';
    if (link.hasClassName(sel))
        link.removeClassName(sel);
    else
        link.addClassName(sel);
    //only one  price-range can be selected
    if (isSingleVal) {
        var items = $('ve_filters_list').getElementsByClassName(className);
        var i, n = items.length;
        for (i = 0; i < n; ++i) {
            if (items[i].hasClassName(sel) && items[i].id != link.id)
                items[i].removeClassName(sel);
        }
    }

    ve_layered_add_params(link.id.split('-')[0], link.id.split('-')[1], isSingleVal);
    ve_layered_make_request();
    Event.stop(evt);
}

function ve_layered_attribute_listener(evt)
{
    ve_layered_add_params('p', 1, 1);
    ve_layered_update_links(evt, 've_layered_attribute', 0);
}

function ve_layered_price_listener(evt)
{
    ve_layered_add_params('p', 1, 1);
    ve_layered_update_links(evt, 've_layered_price', 1);
}

function ve_layered_clear_listener(evt)
{
    var link = Event.findElement(evt, 'A'),
            varName = link.id.split('-')[0];
    ve_layered_add_params('p', 1, 1);
    ve_layered_add_params(varName, 'clear', 1);
    if ('price' == varName) {
        var from = $('adj-nav-price-from'),
                to = $('adj-nav-price-to');
        if (Object.isElement(from)) {
            from.value = from.name;
            to.value = to.name;
        }
    }

    ve_layered_make_request();
    Event.stop(evt);
}

function roundPrice(num) {
    num = parseFloat(num);
    if (isNaN(num))
        num = 0;
    return Math.round(num);
}

function ve_layered_category_listener(evt) {
    var link = Event.findElement(evt, 'A');
    var catId = link.id.split('-')[1];
    var reg = /cat-/;
    if (reg.test(link.id)) { //is search
        ve_layered_add_params('cat', catId, 1);
        ve_layered_add_params('p', 1, 1);
        ve_layered_make_request();
        Event.stop(evt);
    }
//do not stop event
}

function catalog_toolbar_listener(evt) {
//alert(evt);
    catalog_toolbar_make_request(Event.findElement(evt, 'A').href);
    Event.stop(evt);
}

function catalog_toolbar_make_request(href)
{
    var pos = href.indexOf('?');
    if (pos > -1) {
        $('ve_layered_params').value = href.substring(pos + 1, href.length);
    }
    ve_layered_make_request();
}

function catalog_toolbar_init()
{
    var items = $('ve_layered_container').select('.pages a', '.view-mode a', '.sort-by a');
    var i, n = items.length;
    for (i = 0; i < n; ++i) {
        Event.observe(items[i], 'click', catalog_toolbar_listener);
    }
}

function ve_layered_dt_listener(evt) {
    var e = Event.findElement(evt, 'DT');
    e.nextSiblings()[0].toggle();
    e.toggleClassName('ve_layered_dt_selected');
}

function ve_layered_clearall_listener(evt)
{
    var params = $('ve_layered_params').value.parseQuery();
    $('ve_layered_params').value = 'clearall=true';
    if (params['q'])
    {
        $('ve_layered_params').value += '&q=' + params['q'];
    }
    ve_layered_make_request();
    Event.stop(evt);
}

function price_input_listener(evt) {
    if (evt.type == 'keypress' && 13 != evt.keyCode)
        return;
    if (evt.type == 'keypress') {
        var inpObj = Event.findElement(evt, 'INPUT');
    } else {
        var inpObj = Event.findElement(evt, 'BUTTON');
    }

    var sKey = inpObj.id.split('---')[1];
    var numFrom = roundPrice($('price_range_from---' + sKey).value),
            numTo = roundPrice($('price_range_to---' + sKey).value);
    if ((numFrom < 0.01 && numTo < 0.01) || numFrom < 0 || numTo < 0)
        return;
    ve_layered_add_params('p', 1, 1);
    ve_layered_add_params(sKey, numFrom + ',' + numTo, true);
    ve_layered_make_request();
}

function ve_layered_init()
{
    var items, i, j, n,
            classes = ['category', 'attribute', 'icon', 'price', 'clear', 'dt', 'clearall'];
    for (j = 0; j < classes.length; ++j) {
        items = $('ve_filters_list').select('.ve_layered_' + classes[j]);
        n = items.length;
        for (i = 0; i < n; ++i) {
            Event.observe(items[i], 'click', eval('ve_layered_' + classes[j] + '_listener'));
        }
    }

    items = $('ve_filters_list').select('.price-input');
    n = items.length;
    var btn = $('price_button_go');
    for (i = 0; i < n; ++i)
    {
        btn = $('price_button_go---' + items[i].value);
        if (Object.isElement(btn)) {
            Event.observe(btn, 'click', price_input_listener);
            Event.observe($('price_range_from---' + items[i].value), 'keypress', price_input_listener);
            Event.observe($('price_range_to---' + items[i].value), 'keypress', price_input_listener);
        }
    }
// finish new fix code    
}

function create_price_slider(width, from, to, min_price, max_price, sKey)
{
    var price_slider = $('ve_layered_price_slider' + sKey);
    return new Control.Slider(price_slider.select('.handle'), price_slider, {
        range: $R(0, width),
        sliderValue: [from, to],
        restricted: true,
        onChange: function (values) {
            var f = calculateSliderPrice(width, from, to, min_price, max_price, values[0]),
                    t = calculateSliderPrice(width, from, to, min_price, max_price, values[1]);
            ve_layered_add_params(sKey, f + ',' + t, true);
            $('price_range_from' + sKey).update(f);
            $('price_range_to' + sKey).update(t);
            ve_layered_make_request();
        },
        onSlide: function (values) {
            $('price_range_from' + sKey).update(calculateSliderPrice(width, from, to, min_price, max_price, values[0]));
            $('price_range_to' + sKey).update(calculateSliderPrice(width, from, to, min_price, max_price, values[1]));
        }
    });
}

function calculateSliderPrice(width, from, to, min_price, max_price, value)
{
    var calculated = roundPrice(((max_price - min_price) * value / width) + min_price);
    return calculated;
}
function showPopupLoader() {
    var box = jQuery('#confProductLoader');
    if (box.length < 1) {
        jQuery('#ve_layered_container').append('<div id="confProductLoader"><div class="content"><div id="loader"></div></div></div>');
    }
    jQuery('#confProductLoader').bPopup();
}
function closePopupLoader(){
    jQuery('#confProductLoader').bPopup().close();
}
function popupView(obj, isAdd, isDelete) {
    function popupBox(msg, isError) {
        closePopupLoader();
        if (!isDelete) {
            var box = jQuery('#confProductView');
            if (box.length < 1) {
                jQuery('body').append('<div id="confProductView"><span class="button b-close bClose"><span>X</span></span><div class="content"><div id="text"></div></div></div>');
            }
            jQuery('#confProductView .content #text').html(msg);
            if(isError) {
               jQuery('#confProductView .content #nav').hide();
            } else {
                jQuery('#confProductView .content #nav').show();
            }
            jQuery('#confProductView').bPopup();
        }
    }
    function updateCart(resp) {
        var obj = {};
        for (var i in resp.update_blocks) {
            obj = resp.update_blocks[i];
            jQuery(obj.key).html(obj.value);
        }
        if (resp) {
            //check for group product's option
            if (resp.configurable_options_block) {
                if (resp.r == 'success') {
                    //show group product options block
                    popupBox(resp.configurable_options_block);
                } else {
                    if (typeof res.messages != 'undefined') {
                        popupBox(resp.messages);
                    } else {
                        popupBox("Something bad happened", true);
                    }
                }
            } else {
                if (resp.r == 'success') {
                    if (resp.message) {
                        popupBox(resp.message);
                    } else {
                        popupBox('Item was added into cart.');
                    }
                } else {
                    if (typeof resp.messages != 'undefined') {
                        popupBox(resp.messages);
                    } else {
                        popupBox("Something bad happened", true);
                    }
                }
            }
            initAjaxifyEvents();
        } else {
            document.location.reload(true);
        }
    }

    if (isAdd) {
        showPopupLoader();
        if (typeof obj == 'string') {
            new Ajax.Request(
                    obj,
                    {
                        method: 'get',
                        onSuccess: function (data) {
                            var resp = {};
                            if (data && data.responseText) {
                                try {
                                    resp = eval('(' + data.responseText + ')');
                                }
                                catch (e) {
                                    resp = {};
                                }
                            }
                            updateCart(resp);
                        }
                    }
            );
        } else {
            if (typeof obj.form.down('input[type=file]') != 'undefined') {
                //use iframe
                obj.form.insert('<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>');
                var iframe = $('upload_target');
                iframe.observe('load', function () {
                    // Handle the response content...
                    try {
                        var doc = iframe.contentDocument ? iframe.contentDocument : (iframe.contentWindow.document || iframe.document);
                        var res = doc.body.innerText ? doc.body.innerText : doc.body.textContent;
                        res = res.evalJSON();
                        updateCart(resp);
                    } catch (e) {
                        popupBox("Something bad happened", true);
                    }
                });
            } else {
                //use ajax
                var url = obj.form.action,
                        data = obj.form.serialize();
                new Ajax.Request(url, {
                    method: 'post',
                    postBody: data,
                    onSuccess: function (response) {
                        // Handle the response content...
                        try {
                            var res = response.responseText.evalJSON();
                            updateCart(res);
                        } catch (e) {
                            popupBox("Something bad happened", true);
                        }

                    }
                });
            }
        }
    }
}