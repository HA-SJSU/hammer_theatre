
  /*
    *
    *   Javascript Functions
    *   ------------------------------------------------
    *   WP Mobile Menu
    *   Copyright WP Mobile Menu 2017 - http://www.wpmobilemenu.com
    *
    *
    */

    
    "use strict";
    
    jQuery( document ).ready( function() {

      jQuery( document ).on( 'click', '.show-nav-right .mobmenu-push-wrap', function ( e ) { 

        e.preventDefault();
        jQuery( '.mobmenu-right-bt' ).trigger( 'click' );

      });
        
      jQuery( document ).on( 'click', '.show-nav-left .mobmenu-push-wrap', function ( e ) { 

        e.preventDefault();
        jQuery( '.mobmenu-left-bt' ).trigger( 'click' );

      });
  
      if ( jQuery( 'body' ).find( '.mobmenu-push-wrap' ).length <= 0 ) {

        jQuery( 'body' ).wrapInner( '<div class="mobmenu-push-wrap"></div>' );
        jQuery( '.mobmenu-push-wrap' ).after( jQuery( '.mob-menu-left-panel' ).detach() );
        jQuery( '.mobmenu-push-wrap' ).after( jQuery( '.mob-menu-right-panel' ).detach() );
        jQuery( '.mobmenu-push-wrap' ).after( jQuery( '.mob-menu-header-holder' ).detach() ); 
        
        if ( jQuery('.mob-menu-header-holder' ).attr( 'data-detach-el' ) != '' ) {
          jQuery( '.mobmenu-push-wrap' ).after( jQuery(   jQuery('.mob-menu-header-holder' ).attr( 'data-detach-el' ) ).detach() ); 
        }
        
        jQuery( '#wpadminbar' ).appendTo( 'body' );

        jQuery( 'video' ).each( function(){
          if( 'autoplay' === jQuery( this ).attr('autoplay') ) {
            jQuery( this )[0].play();
          } 
        });


      }      
    
      jQuery( document ).on( 'click',  '.mobmenu-left-bt, .mob-menu-left-panel .mobmenu_content a, .show-nav-left .mob-cancel-button' , function ( e ) {  
            
        if ( jQuery(this).parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) || jQuery(this).parent().parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) ) {
          if( 'mobmenuleft' ===  jQuery(this).parent().parent().attr('id') && jQuery(this).parent().find( '.mob-expand-submenu' ).length > 0 )  { 
            jQuery(this).parent().find( '.mob-expand-submenu' ).first().trigger( 'click' );
            return false;
          }
        }

        jQuery('body').toggleClass('show-nav-left'); 
        
        if ( !jQuery( 'body' ).hasClass( 'show-nav-left') ){  

          if ( jQuery( this ).hasClass( 'mob-cancel-button') || jQuery( this ).hasClass( 'mobmenu-left-bt' ) ) {
            return false;
          }

        } else {
            e.preventDefault();
        }

      });

      jQuery( document ).on( 'click', '.mobmenu-right-bt, .mob-menu-right-panel .mobmenu_content a, .show-nav-right .mob-cancel-button' , function ( e ) {

        if ( jQuery(this).parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) || jQuery(this).parent().parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) ) {
          if( 'mobmenuright' ===  jQuery(this).parent().parent().attr('id') && jQuery(this).parent().find( '.mob-expand-submenu' ).length > 0 )  { 
            jQuery(this).parent().find( '.mob-expand-submenu' ).first().trigger( 'click' );
            return false;
          }
        }

        jQuery('body').toggleClass('show-nav-right'); 
        
        if ( !jQuery( 'body' ).hasClass( 'show-nav-right') ){

          if ( jQuery( this ).hasClass( 'mob-cancel-button') || jQuery( this ).hasClass( 'mobmenu-right-bt' ) ) {
              return false;
          }

        } else {
            e.preventDefault();
        }

      });

      jQuery( '.mobmenu_content .sub-menu' ).each( function(){
        
        jQuery( this ).before('<div class="mob-expand-submenu"><i class="mob-icon-down-open"></i><i class="mob-icon-up-open hide"></i></div>');

      });
        
      jQuery( document ).on( 'click', '.mob-expand-submenu' , function ( e ) {

        e.stopPropagation();
            
        if ( jQuery( this ).next().hasClass( 'show-sub-menu' )  ) {
          jQuery(this).find('.show-sub-menu' ).hide();
        }
        if ( ! jQuery( this ).parents('.show-sub-menu').prev().hasClass('mob-expand-submenu') && jQuery( this ).next()[0] !== jQuery('.show-sub-menu')[0] && jQuery( this ).parent('.sub-menu').length <= 0 ) {
          jQuery(this).find('.mob-icon-down-open').removeClass('hide');
          jQuery(this).find('.mob-icon-up-open').addClass('hide');
          jQuery(this).find( '.show-submenu' ).hide().toggleClass( 'show-sub-menu' );
          
        }
        
        jQuery( this ).find('.mob-icon-down-open').toggleClass('hide');
        jQuery( this ).find('.mob-icon-up-open').toggleClass('hide');
        
        if ( !jQuery( this ).next().hasClass( 'show-sub-menu' ) ) {  
          jQuery(this).next().fadeIn( 'slow' );   
        } else {  
          jQuery(this).next().hide();   
        }

        jQuery(this).next().toggleClass( 'show-sub-menu');
        
      });

    }); 