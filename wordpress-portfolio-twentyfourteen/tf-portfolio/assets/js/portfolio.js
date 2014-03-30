(function( $ ) {
	$.TFPortfolio = function( element ) {
	  if( $( element ).length ) {
		this.$element = $( element );
		this.init();
	  }
	}
	
	$.TFPortfolio.prototype = {
		init: function() {
			this.$links = this.$element.find( ".portfolio-lightbox" );
			
			this.lightbox();	
			
		},
		
		lightbox: function() {
			this.$links.colorbox();
		}	
		
	};
	
	$(function() {
		var portfolio = new $.TFPortfolio( "#portfolio-items" );
		
	});
	
})( jQuery );