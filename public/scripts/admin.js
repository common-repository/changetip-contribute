(function($) {
    $(function() {
        var $body = $('body');
        var $modalCurtain = $('#changepay-modal-curtain')
        var $modals = $('.changepay-modal');
        var $links = $('[data-changepay-modal-link]');

        var listeners = {};
        listeners['connect_site'] = function(data) {
            var siteId = data.site.uuid;
            if(!siteId) {
                console.error('Site id not found in jsondata returned by Changepay: ');
                console.error(data);
                return;
            }
            var $siteIdInput = $('input[name="changepay_site_id"]');
            $siteIdInput.val(siteId);
            closeAllModals();
            $modals.find('iframe').remove();
            $('.changepay-form .submit input').click();
        };

        window.__changepay__receiveMessage = function(event) {
            var origin = event.origin || event.originalEvent.origin;
            var jsonData = {};
            try {
                jsonData = JSON.parse(event.data);
            } catch(err) {
                return console.error(err);
            }

            console.log(jsonData);
            if(origin === 'https://www.changetip.com'
                && jsonData
                && jsonData.message
                && jsonData.data
                && listeners[jsonData.message]) {
                    listeners[jsonData.message](jsonData.data);
            }
        }

        window.addEventListener("message", __changepay__receiveMessage, false);

        function $bodyClickCloseModal(e) {
            var $target = $(e.target);
            if($target.is('.changepay-modal-align') || $target.closest('.changepay-modal-align').length) return;
            closeAllModals();
            $body.unbind('click.closemodal');
        }

        function preloadModal($modal) {
            var src = $modal.data().src;
            if(!src) return;
            var $content = $modal.find('.changepay-modal-align');
            var $iframe = $('<iframe />');

            $content.html('');
            $iframe.attr('src', src);
            $content.append($iframe);
        }

        function closeAllModals() {
            $modals.removeClass('open').hide();
            $modalCurtain.hide();
        }

        function $clickToggleModalLink() {
            var $link = $(this);
            var modal = $link.data().changepayModalLink;
            var $modal = $modals.filter('[data-changepay-modal="' + modal + '"]')
            if(!$modal.length) return console.error('Changetip Contribute modal not found: ' + modal);

            var isOpen = $modal.hasClass('open');
            closeAllModals();
            $modal.toggleClass('open');

            if(!isOpen) {
                preloadModal($modal);
                $modal.show();
                $modalCurtain.fadeIn();
                $body.bind('click.closemodal', $bodyClickCloseModal);
            }
            return false;
        }

        $links.click($clickToggleModalLink);
    });
})(jQuery);
