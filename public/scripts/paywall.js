(function($) {
    $(function() {
        if(!window.changePay) {
            return console.error('ChangePay SDK not loaded.');
        }

        var $contentWrapper = $('#changepay-content');
        var context = $contentWrapper.data();
        if(!$contentWrapper.length || !context) return;

        var getListeners = function() {
            var listeners = {};
            $('#changepay-messages .changepay-message').each(function() {
                var $message = $(this);
                var key = $message.data().key;
                var type = $message.data().type;
                var content = $message.html();
                listeners[key] = function() { changePay.UI.showMessage(content, type); };
            });

            //console.log(listeners);
            return listeners;
        }

        var initializePay = function() {
            //TODO: encapsulate $ in SDK js, workaround for now:
            if(!window.$) window.$ = $;

            if(!context.siteId) {
                return console.error('data-site-id attribute required for #changepay-content element');
            }

            changePay.debug = context.debug;

            changePay.init({
                site_id: context.siteId,
                ready: function(login_status) {
                    if(!login_status) updateContentForUnauthenticated();
                },
                paid: function(status_code, msg) {
                    updateContentForPaid();
                },
                unpaid: function(status_code, msg) {
                    updateContentForUnpaid();
                },
                listeners: getListeners()
            });
        }

        var updateContent = function(action) {
            $.post(context.ajaxurl, {
                action: action,
                nonce: context.nonce,
                post_id: context.postId
            }, function(response) {
                if(!response.content) {
                    console.error('no post content was returned:');
                    console.log(response);
                    return;
                }
                $contentWrapper.html(response.content);
            });
        }

        var updateContentForPaid = function() {

            updateContent('the_content_paid');
        }

        var updateContentForUnpaid = function() {
            if(context.googleads) {
                var $meta = $('meta[name="adsbygoogle"]');
                var $script = $('<script />');
                $script.attr('src', $meta.attr('description'));
                document.head.appendChild($script.get(0));
                (adsbygoogle = window.adsbygoogle || []).push({});
            }
            updateContent('the_content_unpaid');
        }

        var updateContentForUnauthenticated = function() {
            updateContent('the_content_unauthenticated');
        }

        //let's go!
        initializePay(context);
    });
})(jQuery);
