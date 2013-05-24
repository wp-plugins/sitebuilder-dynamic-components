SB_DC_Components = ( function(){
	
	return {

		init: function(){

			this.reload();

		},

		hashKeys: function( obj ){

			var keys = [];

		    for( var key in obj ){

		        if( obj.hasOwnProperty( key ) ){

		            keys.push(key);

		        }

		    }

		    return keys;

		},

		reload: function(){

			var self = this;

	        var args = {
	            'action'		:	'sb_dc_render_dynamic_components',
			    'components'	: 	SB_DC_COMPONENTS,
			    'args'			: 	SB_DC_PAGE_ARGS,
			    '_t'			:   new Date().getTime()
	        };

	        jQuery.get(
	            SB_DC_AJAXURL, 
				args, 
	            function( data ) {            

					data = jQuery.parseJSON( data );
																	
					var user_logged_in = ( data[ 'is_user_logged_in' ] != undefined ) ? data[ 'is_user_logged_in' ] : false;
					var user_data = ( data[ 'user_data' ] != undefined ) ? data[ 'user_data' ] : [];

	                if ( user_logged_in && user_data ){	                    

	                    jQuery( 'body' ).trigger( 'sb_dc_user_data_available', [ user_logged_in, user_data ] );

					}
					
					var keys = self.hashKeys( data );					
					var i = keys.length - 1;
					
					do {

						var component = keys[ i ];
						var component_html = data[ component ];

						if( jQuery( "#sb-dc-" + component ).length ) {

							jQuery( "#sb-dc-" + component ).html( component_html );
							jQuery( 'body' ).trigger( 'sb_dc_dynamic_component_loaded', [ component, component_html ] );

						}

					} while( i-- );							
				    
				}

			);

		}

	};

}());

SB_DC_Components.init();