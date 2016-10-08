$(function() {
  // Parallax sections are handled with GSAP and ScrollMagic
  var parallaxSections = $('.parallax-section' );
  var controller = new ScrollMagic.Controller( {
    globalSceneOptions : {
      triggerHook : "onEnter",
      offset: 0,
      duration    : "150%"
    }
  } );



  if ( parallaxSections.length ) {


    // Setup each parallax section on the page.
    parallaxSections.each(function(_, elem) {
      var SECTION_ID = elem && ( '#' + elem.id );

      // The section needs to have an ID
      if ( SECTION_ID ) {
        var scene = new ScrollMagic.Scene( { triggerElement : SECTION_ID } );
        scene.setTween( SECTION_ID + ' .parallax-background', { y : '30%', ease : Linear.easeNone } );
        scene.addTo( controller );
      }
    }); // END EACH

  } // END IF;

});