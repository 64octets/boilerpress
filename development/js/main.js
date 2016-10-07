(function ( $, window, document ) {
  var $body       = $( 'body' );
  var breakpoints = {
    xs : 0,
    sm : 540,
    md : 768,
    lg : 992,
    xl : 1200
  };

  var mediaQueries = {};

  Object.keys( breakpoints ).forEach( function ( key ) {
    mediaQueries[key] = window.matchMedia( '(min-width:' + breakpoints[ key ] + 'px)' );
  } );

  function mobileHeroContent(mq) {
    let elem = $('#mobile-hero-content' );
    if (mq.matches) {
      TweenMax.to(
        elem, 0.4, {
          height: 0,
          delay: 0.2,
          ease: Power2.easeOut,
          display: 'none',
        }
      )
    } else {
      TweenMax.to(
        elem, 0.4, {
          height: '275px',
          delay: 0.2,
          ease: Power2.easeOut,
          display: 'block'
        }
      )
    }
  }
  mediaQueries['md' ].addListener(mobileHeroContent);
  mobileHeroContent(mediaQueries['md' ]);

  //=require theme/functions.js
  //=require theme/navigation.js
  // =require theme/mobile-nav-drawer.js
  //=require theme/food-menu.js
  //=require theme/animation.js
  //=require theme/parallax-section.js


})( jQuery, window, document );