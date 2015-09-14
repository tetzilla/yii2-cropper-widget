// Baesd on http://fengyuanchen.github.io/cropper/js/main.js

(function ($) {
 
    $.fn.myCropper = function(options) {
    	
    	var $image = this;
    	var $dataX = $('#dataX');
		var $dataY = $('#dataY');
		var $dataHeight = $('#dataHeight');
		var $dataWidth = $('#dataWidth');
		var $dataRotate = $('#dataRotate');
		var $dataScaleX = $('#dataScaleX');
		var $dataScaleY = $('#dataScaleY');
		
		var $src = $('.image-src');
		var $data = $('.image-data');
		
		$image.on().cropper(options);
		
		//var json = [options].join();
		//alert(json.toSource());
		//$data.val(json);
		
	    $(document.body).on('click', '[data-cropper-method]', function () {
	    	var data = $(this).data();
	        var $target;
	        var result;

	        if (!$image.data('cropper')) {
	          return;
	        }

	        if (data.cropperMethod) {
	        	data = $.extend({}, data); // Clone a new one
	        	if (typeof data.target !== 'undefined') {
	        		$target = $(data.target);
	        	}
	            if (typeof data.option === 'undefined') {
	            	try {
	            		data.option = JSON.parse($target.val());
	            	} catch (e) {
	            		console.log(e.message);
	            	}
	            }
	        }

	        result = $image.cropper(data.cropperMethod, data.option, data.secondOption);

	        
	        
	        if (data.flip === 'horizontal') {
	            $(this).data('option', -data.option);
	        }
	        if (data.flip === 'vertical') {
	            $(this).data('secondOption', -data.secondOption);
	        }
	        if (data.method === 'getCroppedCanvas') {
	            $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
	        }
	        if ($.isPlainObject(result) && $target) {
	        	try {
	        		$target.val(JSON.stringify(result));
	            } catch (e) {
	            	console.log(e.message);
	            }
	        }
	    }).on('keydown', function (e) {
	        if (!$image.data('cropper')) {
	        	return;
	        }
	        if (this.scrollTop > 300) {
	        	return;
	        }
	        switch (e.which) {
	        	case 37:
	          		e.preventDefault();
	          		$image.cropper('move', -1, 0);
	          		break;
	        	case 38:
	        		e.preventDefault();
	        		$image.cropper('move', 0, -1);
	        		break;
	        	case 39:
	        		e.preventDefault();
	        		$image.cropper('move', 1, 0);
	        		break;
	        	case 40:
	        		e.preventDefault();
	        		$image.cropper('move', 0, 1);
	        		break;
	        }
	    });
	    
	    // Import image
	    var $inputImage = $('#inputImage');
	    var URL = window.URL || window.webkitURL;
	    var blobURL;

	    if (URL) {
	    	$inputImage.change(function () {
	    		var files = this.files;
	    		var file;

	    		if (!$image.data('cropper')) {
	    			return;
	    		}

	    		if (files && files.length) {
	    			file = files[0];
	    			if (/^image\/\w+$/.test(file.type)) {
	    				blobURL = URL.createObjectURL(file);
	    				$image.one('built.cropper', function () {
	    					URL.revokeObjectURL(blobURL); // Revoke when load complete
	    				}).cropper('reset').cropper('replace', blobURL);
	    				//alert($inputImage.val());
	    				//$inputImage.val('');
	    		        var data    = $image.cropper('getData');
	    		        var original= $image.cropper('getImageData');
	    		        jQuery.extend(data,original) 
	    		        var value = JSON.stringify(data);
	    		        $data.val(value); 	    				
	    				$src.val('');
	    			} else {
	    				$body.tooltip('Please choose an image file.', 'warning');
	    			}
	    		}
	    	});
	    } else {
	    	$inputImage.parent().remove();
	    }


	    // Options
	    $('.docs-options :checkbox').on('change', function () {
	    	var $this = $(this);
	    	var cropBoxData;
	    	var canvasData;

	    	if (!$image.data('cropper')) {
	    		return;
	    	}

	    	options[$this.val()] = $this.prop('checked');

	    	cropBoxData = $image.cropper('getCropBoxData');
	    	canvasData = $image.cropper('getCanvasData');
	    	options.built = function () {
	    		$image.cropper('setCropBoxData', cropBoxData);
	    		$image.cropper('setCanvasData', canvasData);
	    	};

	    	$image.cropper('destroy').cropper(options);
	    });
	    
    };
}( jQuery ));
