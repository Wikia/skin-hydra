/**
 *  Magical Footer Mover.
 *  Make sure its always below the nav panel.
 */
(function(){
    $footer = $('footer');
    $nav = $('#mw-panel');
    new ResizeSensor($nav[0],function(){ handleResizeEvents(); });
    $( window ).resize(function(){ handleResizeEvents(); });
    handleResizeEvents = function(){
        var navHeight = $nav.height() + $nav.position()['top'];
        var footerTop = $footer.position()['top'];
        if (footerTop < navHeight) {
            var newTop = (navHeight - footerTop) + 50;
            $footer.css('margin-top',newTop+'px');
        } else {
            $footer.css('margin-top','0px');
        }
    };
}());