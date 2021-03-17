jQuery(function ($) {
  $(document).ready(function () {
    $(".sbw_sidebar-widget__menu-heading").click(function () {
      $(this).next().toggleClass("sbw-hidden");
    });

    $(".sbw_sidebar-widget__category-heading").click(function () {
      $(this).next().toggleClass("sbw-hidden");
    });

    //price slider
    var minPrice = $("#amount_min").val();
    var maxPrice = $("#amount_max").val();

    $("#anps-price-range-slider").slider({
      range: true,
      step: 10,
      min: parseInt(minPrice),
      max: parseInt(maxPrice),
      values: [parseInt(minPrice), parseInt(maxPrice)],
      slide: function (event, ui) {
        $("#amount_min").replaceWith(
          `<input id='amount_min' type='text' value='${ui.values[0]}' style="display:none;"/>`
        );
        $("#amount_max").replaceWith(
          `<input id='amount_max' type='text' value='${ui.values[1]}' style="display:none;"/>`
        );
        $(".value-left").text(`Min: ${ui.values[0]}`);
        $(".value-right").text(`Max: ${ui.values[1]}`);

      },
      stop: function (event, ui) {
        sendAjaxRequest()
      },
    });
    const widget = $('.sbw_sidebar-widget');
    if (widget.length > 0) {
      const inputs = widget.find('input')
      inputs.each(function () {
        if ($(this).is(':checkbox')) {
          $(this).on('click', function () {
            sendAjaxRequest();
          })
        }
      })
    }
  });

  function sendAjaxRequest() {
    const inputCategory = $('.sbw_sidebar-widget__category .sbw_sidebar-widget__category-group-1').find('input');
    const inputOnSale = $('.sbw_sidebar-widget__category .sbw_sidebar-widget__category-group-2').find('input');
    const inputAttr = $('.sbw_sidebar-widget__menu').find('input');
    const inputMaxPrice = $('.sbw_sidebar-widget__price').find('#amount_max').val()
    const inputMinPrice = $('.sbw_sidebar-widget__price').find('#amount_min').val()

    let attrArreyToSend = []
    let catArrayToSend = []
    let onSaleArrayToSend = []

    console.log(catArrayToSend)

    inputAttr.map(function () {
      if ($(this).is(':checked')) {
        const type = $(this).attr('id')
        const value = $(this).val()
        attrArreyToSend.push({ type, value })
      }
    })

    inputCategory.map(function () {
      if ($(this).is(':checked')) {
        catArrayToSend.push($(this).val())
      }
    })

    inputOnSale.map(function () {
      if ($(this).is(':checked')) {
        onSaleArrayToSend.push($(this).val())
      }
    })

    $.ajax({
      method: 'post',
      url: ajax_main.ajaxurl,
      dataType: 'json',
      data: {
        action: 'anps_filter_products_ajax',
        categories: catArrayToSend,
        attributes: attrArreyToSend,
        onsale: onSaleArrayToSend,
        minPrice: inputMinPrice,
        maxPrice: inputMaxPrice
      },

    }).done(function (response, textStatus, XMLHttpResponse) {
      if (textStatus === 'success') {
        const html = response[0];
        const urlDataSetup = response[1];
        $('.products').empty()
        $('.products').append(html)

        doneUrlSetup(urlDataSetup)

      }
    })

  }

  function doneUrlSetup(urlData) {
    const output = urlData.attributes.reduce(function (o, cur) {
      let occurs = o.reduce(function (n, item, i) {
        return (item.type === cur.type) ? i : n;
      }, -1);
      if (occurs >= 0) {
        o[occurs].value = o[occurs].value.concat(cur.value);
      } else {
        let obj = {
          type: cur.type,
          value: [cur.value]
        };
        o = o.concat([obj]);
      }
      return o;
    }, []);
    console.log(output)

    const newurl = `${window.location.protocol}//${window.location.host + window.location.pathname}?${urlData.categories ? 'product_cat=' + urlData.categories.join(',') : ''}${urlData.minPrice !== '' ? '&min_price=' + urlData.minPrice : ''}${urlData.maxPrice !== '' ? '&max_price=' + urlData.maxPrice : ''}`;
    console.log(newurl)
    window.history.pushState({ path: newurl }, '', newurl);
  }

});
