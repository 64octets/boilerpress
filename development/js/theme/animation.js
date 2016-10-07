$(window ).on('load', function() {
    var parallaxSectionOne = $("#parallax-section-one");
    var parallaxSectionTwo = $("#parallax-section-two");
    if (parallaxSectionOne.length && parallaxSectionTwo.length && 0) {
        TweenMax.set(".headline.display-1", {y:'-200px', scale:0.2, opacity: 0.0})
        // init controller
        var controller = new ScrollMagic.Controller( {
            globalSceneOptions : {
                triggerHook : "onEnter",
                offset: 0,
                duration    : "200%"
            }
        } );

        // build scenes
        new ScrollMagic.Scene( { triggerElement : "#parallax-section-one" } )
        .setTween( "#parallax-section-one > .parallax-background", { y : "30%", ease : Linear.easeNone } )
        .addTo( controller );

        // build scenes
        new ScrollMagic.Scene( { triggerElement : "#parallax-section-one" } )
        .setTween( ".headline.display-1", { y: '0px' , scale: 1.0,  opacity: 1.0, ease : Linear.easeNone } )
        .addTo( controller );

        new ScrollMagic.Scene( { triggerElement : "#parallax-section-two" } )
        .setTween( "#parallax-section-two > .parallax-background", { y : "30%", ease : Linear.easeNone } )
        .addTo( controller )

    }
});