/**
 * Smoothly scrolls the page to a specific location, either a target element
 * or an arbitrary offset value (in pixels). By default, the duration of the
 * scroll animation is set relative to the distance being scrolled.
 *
 * @param  target: the target element to scroll to
 * @param  params: an object with custom options for scroll the animation. Takes the
 *         same options as a velocity call (see velocity.js)
 */

function isObject(obj) {
    return typeof obj === 'object' && obj !== null;
}

var SmoothScroll = {

};
function smoothScroll(target, params) {
    target = $(target);
    var targetNode = target.get(0),
    scrollDistance, options;

    if ( !targetNode ) {
        return;
    }

    if ( targetNode === document.body || targetNode === window.window ) {
        scrollDistance = 0;
    } else {
        scrollDistance = (Math.abs(target.offset().top - window.pageYOffset)) * 0.25;
    }


    options = $.extend( {}, {
        duration: Math.max(750, Math.min(scrollDistance, 2000)),
        ease: Power2.easeOut,
        offset: 0,
    }, isObject(params) ? params : {} );

    if (window.Velocity) {
        // stop all active animations on the target and body element before starting
        // In case a scroll animation is currently in progress.
        $('html' ).velocity('stop');
        return target.velocity('stop').velocity('scroll', options);

    } else {
        var duration = ( options.duration * 0.001 );
        TweenLite.to(window, duration, {
            scrollTo: targetNode.id ? {
                y: '#' + targetNode.id,
                offsetY: options.offset || 0
            } : 0
        });
    }

}

function killSmoothScroll() {
    if ( State.smoothScrolling && isObject(State.smoothScrolling) ) {
        $.Velocity(State.smoothScrolling(), 'stop');
    }
}


function trailingslashit(str) {
    if ( typeof str !== 'string' || !str.length ) return;
    return str[str.length - 1] === '/' ? str : ( str + '/' );
}
function untrailinglashit(str) {
    if ( typeof str !== 'string' || str.length === 0 ) return;
    return str[str.length - 1] === '/' ? str.substr(0, str.length - 1) : str;
}
