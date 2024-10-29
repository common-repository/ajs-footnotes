/**
 * This file is part of the AJS Footnotes Wordpress Plugin, by Adam J. Seidl.
 * See http://www.ajseidl.com/projects/ajs-footnotes/ for details & support.
 * @version 2.0
 */
jQuery(function($){
	var docDim, noteVPos, noteHPos, doNothing, adjustments;
	noteVPos = 'top';
	noteHPos = 'right';
	docDim = { 'width': $(document).width(), 'height': $(document).height() };
	doNothing = false;
	//top is an ABSOLUTE measure of distance FROM the note link
	adjustments = { top: 0, left: 22 };

	$('.ajs-footnote-popup').css({});
	//get the link containers
	$('.ajs-footnote>a').hoverIntent({
			over: function( event ) {
				var noteNumber, noteWidth, thisNote, noteVdelta, noteHdelta, eOffset;
				//get note number
				noteNumber = ($(event.target).attr('href')).substring(($(event.target).attr('href')).lastIndexOf('_')+1);
				thisNote = '#ajs-fn-id'+'_'+noteNumber;
				noteWidth = $(thisNote)
					.css({display:'none', visibility:'hidden'})
					.outerWidth();
				noteHeight = $(thisNote).outerHeight();
				
				//Math time...
				/**
				 * TODO: Adjust V & H for document fit
				 */
				switch ( noteVPos ) {
				case 'top':
					noteVdelta = -1* (noteHeight + adjustments.top);
					$(thisNote).css({paddingBottom: $(event.target).height()+adjustments.top});
					break;
				case 'bottom':
					noteVdelta = 0;
					$(thisNote).css({paddingTop: $(event.target).height()+adjustments.top})
					break;
				default:
					noteVdelta = -1*Math.round(noteHeight/2 -  $(event.target).height()/2);
				} //end noteVPos switch
				
				switch ( noteHPos ) {
				case 'right':
					noteHdelta = -1*(noteWidth - adjustments.left);
					( adjustments.left <= 0 ) ? $(thisNote).css({paddingRight: $(event.target).width() + -1*(adjustments.left) }) : noteHdelta += adjustments.left;
					break;
				case 'center':
					noteHdelta = -1*Math.round(noteWidth/2);
					break;
				default:
					noteHdelta = 0;
					( adjustments.left >= 0 ) ? $(thisNote).css({ paddingLeft: $(event.target).width()+adjustments.left }) : noteHdelta = adjustments.left;
				} //end noteHPos switch
				
				//get the event offset
				eOffset = $(event.target).offset();
				console.log(eOffset.top);
				
				$(thisNote).css({position:'absolute', top: eOffset.top+noteVdelta+'px', left: eOffset.left+noteHdelta+'px', display: 'block', visibility:'visible'});
				console.log(noteWidth+ ' X '+noteHeight);
			}, 
			out: function( event ) {
				var noteNumber, thisNote;
				
				noteNumber = ($(event.target).attr('href')).substring(($(event.target).attr('href')).lastIndexOf('_')+1);
				thisNote = '#ajs-fn-id'+'_'+noteNumber;
				if( !doNothing ) {
					$(thisNote).css({display:'none'});
				}
			},
			timeout: 500
	});//end .ajs-footnote hoverIntent
	$('.ajs-footnote-popup').hoverIntent({
		over: function(event) {
			doNothing = true;
		},
		out: function(event) {
			doNothing = false;
			$(this).hide();
		},
		timeout:500,
	});
	
});