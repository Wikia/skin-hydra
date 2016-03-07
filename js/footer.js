/**
 *  Magical Footer Mover.
 *  Make sure its always below the nav panel.
 */

$(function(){
    $footer = $('footer');
    $nav = $('#mw-panel');
    if (typeof $footer !== 'undefined'
        && typeof $nav !== 'undefined'
        && typeof $nav[0] !== 'undefined'
    ) {
        new ResizeSensor($nav[0],function(){ handleResizeEvents(); });
        $( window ).resize(function(){ handleResizeEvents(); });
    }
    handleResizeEvents = function(){
        var navPosition = $nav.position();
        var footerPosition = $footer.position();
        if (navPosition
            && footerPosition
            && typeof navPosition.top !== 'undefined'
            && typeof footerPosition.top !== 'undefined') {
                var navHeight = $nav.height() + navPosition.top;
                var footerTop = footerPosition.top;
                if (footerTop < navHeight) {
                    var newTop = (navHeight - footerTop) + 50;
                    $footer.css('margin-top',newTop+'px');
                } else {
                    $footer.css('margin-top','0px');
                }
        }
    }

});