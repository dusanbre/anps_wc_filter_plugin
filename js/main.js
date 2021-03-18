jQuery(function ($) {
  var postPerPage = 9;
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

    var getMinPrice = $(".value-left").attr('id');
    var getMaxPrice = $(".value-right").attr('id');

    console.log(minPrice, maxPrice, getMinPrice, getMaxPrice)

    $("#anps-price-range-slider").slider({
      range: true,
      step: 10,
      min: parseInt(minPrice),
      max: parseInt(maxPrice),
      values: [parseInt(getMinPrice), parseInt(getMaxPrice)],
      slide: function (event, ui) {
        $(".value-left").text(`Min: ${ui.values[0]}`);
        $(".value-right").text(`Max: ${ui.values[1]}`);
        $(".value-left").attr('id', ui.values[0]);
        $(".value-right").attr('id', ui.values[1]);

      },
      stop: function (event, ui) {
        sendAjaxRequest()
      },
    });

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

  function sendAjaxRequest() {
    const inputCategory = $('.sbw_sidebar-widget__category .sbw_sidebar-widget__category-group-1').find('input');
    const inputOnSale = $('.sbw_sidebar-widget__category .sbw_sidebar-widget__category-group-2').find('input');
    const inputAttr = $('.sbw_sidebar-widget__menu').find('input');
    const inputMinPrice = $('.sbw_sidebar-widget__price').find('.value-left').attr('id')
    const inputMaxPrice = $('.sbw_sidebar-widget__price').find('.value-right').attr('id')

    let attrArreyToSend = []
    let catArrayToSend = []
    let onSaleArrayToSend = []

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
      beforeSend: function () {
        // Show image container
        $("body").append('<div class="psd-loader loader">Loading</div>');
        $('body .site').css('opacity', '0.2')
      },

    }).done(function (response, textStatus, XMLHttpResponse) {
      if (textStatus === 'success') {
        const html = response[0];
        const urlDataSetup = response[1];
        $('.products').empty()
        $('.products').append(html)
        $('.loader').remove();
        $('body .site').css('opacity', '1')
        $('.products li').addClass('is-ajax')
        $('.woocommerce-pagination').empty()
        let index = 0;
        if ($('.products li').length > postPerPage) {
          $('.woocommerce-pagination').empty().append('<button id="anps-wc-load-more">Load More</button>')
          $('.woocommerce-pagination').css('text-align', 'center')
          $('.products li').each(function () {
            index++
            if (index > postPerPage) {
              $(this).addClass('is-ajax-hidden')
            }
          })
        }
        doneUrlSetup(urlDataSetup)
      }
    })

  }

  //Function for setup get url
  function doneUrlSetup(urlData) {
    if (urlData.attributes) {
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
      var urlString = output.map(item => {
        return 'filter_' + item.type + '=' + item.value.join(',')
      })
    }

    const newurl = `${window.location.protocol}//${window.location.host + window.location.pathname}?${urlData.categories ? 'product_cat=' + urlData.categories.join(',') : ''}${urlData.minPrice !== '' ? '&min_price=' + urlData.minPrice : ''}${urlData.maxPrice !== '' ? '&max_price=' + urlData.maxPrice : ''}${urlString ? '&' + urlString.join('&') : ''}`;
    console.log(newurl)
    window.history.pushState({ path: newurl }, '', newurl);
  }

  //Load more button
  $('body').on('click', '#anps-wc-load-more', function (e) {
    e.preventDefault()
    postPerPage += 9
    var index = 0;
    $('.products li').each(function () {
      index++
      if (index <= postPerPage) {
        $(this).removeClass('is-ajax-hidden')
      }
    })
  })


});
