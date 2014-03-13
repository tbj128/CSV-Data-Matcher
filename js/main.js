/*
 * EpicWheels App Manager 
 * @author: Tom Jin
 */
window.EpicWheels = window.EpicWheels || {};

EpicWheels.Main = (function($) {
	"use strict"
	var config = {},

	init = function(options) {
		// default options (selectors, jQuery objects, etc)
		var defaults = {
			requiresArray : new Array()
		};
		$.extend(true, config, defaults, options);

		_onDomReady();
		_attachEventHandlers();
	},

	_onDomReady = function() {

		_prepareSideMenu();

	},
	
	_prepareSideMenu = function() {
	    // Find list items representing folders and
		// style them accordingly.  Also, turn them
		// into links that can expand/collapse the
		// tree leaf.
		$('li.left > ul').each(function(i) {
			// Find this list's parent list item.
			var parent_li = $(this).parent('li');

			// Style the list item as folder.
			parent_li.addClass('folder');

			// Temporarily remove the list from the
			// parent list item, wrap the remaining
			// text in an anchor, then reattach it.
			var sub_ul = $(this).remove();
			parent_li.find('a').click(function() {
				// Make the anchor toggle the leaf display.
				sub_ul.toggle();
				$('li.left a').removeClass("selected");
				parent_li.find('> a').addClass("selected");
				if (sub_ul.is(':visible')) {
					parent_li.find('> a > img').attr("src", "img/arrow_down.png");
				} else {
					parent_li.find('> a > img').attr("src", "img/arrow_right.png");
				}
			});
			parent_li.append(sub_ul);
		});

		// Hide all lists except the outermost.
		$('ul.left ul').hide();
		
	},
	
	_attachEventHandlers = function() {
		$('.side-nav-item').click(function(){
			var item_text_path = $(this).data("textpath");
			var item_path = $(this).data("path");
			var item_clicked = $(this).data("child");
			$('#alert-no-items').hide();
			$('.edit-window').hide();
			$('#' + item_clicked).show();
			
			var text_path = item_text_path.split(",");
			var id_path = item_path.split(",");
			$('#breadcrumb-menu').empty();
			for (var i=0; i<text_path.length; i++) {
				if (i == (text_path.length - 1)) {
					$('#breadcrumb-menu').append("<li><i class=\"fa fa-list\"></i> " + text_path[i] + "</li>");
				} else {
					$('#breadcrumb-menu').append("<li class=\"active\"><i class=\"fa fa-list\"></i> " + text_path[i] + "</li>");
				}
			}
			// TODO: Work on clickable breadcrumbs

		});
	
		$('#btn-requires').click(function(){
			$('#requires-picker').show();
		});
		
		$('.btn-close-requires').click(function(){
			$('#requires-picker').hide();
		});
	
	
	
	
	
	
	
	
	
		// Close Handlers
		$('#edit-menu-close').click(function(){
			$('#edit-menu').hide();
		});
		$('#edit-video-close').click(function(){
			$('#edit-video').hide();
		});
		$('#edit-options-close').click(function(){
			$('#edit-choose-options').hide();
		});
		
		// Create Options
		$('#create-new-menu').click(function(){
			$('#edit-choose-options').hide();
			$('#edit-menu').show();
		});
		$('#create-new-video').click(function(){
			$('#edit-choose-options').hide();
			$('#edit-video').show();
		});
		// General create menu shows up
		$('.preview-add-item ').click(function(){
			// Clear any previous input entries
			$('#videoID').val('');
			$('#videoName').val('');
			$('#videoType').val('0').attr('selected',true);
			$('#videoPath').val('');
			$('#video-datapath').val('');
			$('#videoRequires').val('').attr('selected',true);
			$('#videoDesc').val('');
			$('.fileinput-button').show();
			$('#videoPath').hide();
			
			$('#menuName').val('');
			$('#menuID').val('');
			$('#requiresMenuPreview').val('');
			$('#menuRequires').val('').attr('selected',true);
			$('#menuDesc').val('');
			
			// Hides delete buttons
			$('#menu-delete').hide();			
			$('#video-delete').hide();			
			
			// Reveals general menu
			$('#edit-choose-options').show();
			
			// Finds and adds datapath
			var pathToRoot = $(this).data("path");
			var parentID = $(this).data('parent');
			
			$('#menu-datapath').val(pathToRoot);
			$('#video-datapath').val(pathToRoot);
			
			$('#videoParentID').val(parentID);
			$('#menuParentID').val(parentID);
			for (var i = 0; i < pathToRoot.length; i++) {
				console.log(pathToRoot[i]);
			}
		});
		
		// Triggered when existing video tile is selected. (Edit Video)
		$('.preview-video-item').click(function() {
			var pathToElement = $(this).data("path");
			var pathID = $(this).data("id");
			$.ajax({
				url: 'includes/get_item.php',
				data: {path: pathToElement, id: pathID},
				type: 'post',
				success: function(output) {
					console.log(output);
					$('#videoName').val($.parseJSON(output).name);
					$('#videoID').val($.parseJSON(output).id);
					$('#videoType').val($.parseJSON(output).type).attr('selected',true);
					$('#videoPath').val($.parseJSON(output).path);
					$('#videoPathEdited').val("0");
					$('#video-datapath').val(pathToElement);
					
					$('input[type="checkbox"]').prop('checked', false); // Reset checkbox
					var childArray = $.parseJSON(output).requires.split(',');
					config.requiresArray = childArray;
					var numRequires = 0;
					for (var i=0;i<childArray.length;i++) {
						if (childArray[i] != "") {
							document.getElementById('cb-' + childArray[i]).checked = true;
							numRequires++;
						}
					}
					
					$('#numberOfRequiresVideo').html(numRequires);
					$('#videoDesc').val($.parseJSON(output).desc);	
					$('#video-delete').show();		
					$('#edit-video').show();
					if ($.parseJSON(output).path != '') {	
						$('.fileinput-button').hide();
						$('#videoPath').show();
						$('#deleteExistingVideo').show();
					} else {
						$('.fileinput-button').show();
						$('#videoPath').hide();
						$('#deleteExistingVideo').hide();
					}
				}
			});
			
		});
		//  Triggered when existing activity tile is selected. (Edit Activity)
		$('.preview-activity-item').click(function() {
			var pathToElement = $(this).data("path");
			var pathID = $(this).data("id");
			$.ajax({
				url: 'includes/get_item.php',
				data: {path: pathToElement, id: pathID},
				type: 'post',
				success: function(output) {
					console.log(output);
					$('#videoName').val($.parseJSON(output).name);
					$('#videoID').val($.parseJSON(output).id);
					$('#videoType').val($.parseJSON(output).type).attr('selected',true);
					$('#videoPath').val($.parseJSON(output).path);
					$('#videoPathEdited').val("0");
					$('#video-datapath').val(pathToElement);
					
					$('input[type="checkbox"]').prop('checked', false); // Reset checkbox
					var childArray = $.parseJSON(output).requires.split(',');
					config.requiresArray = childArray;
					var numRequires = 0;
					for (var i=0;i<childArray.length;i++) {
						if (childArray[i] != "") {
							document.getElementById('cb-' + childArray[i]).checked = true;
							numRequires++;
						}
					}
					
					$('#numberOfRequiresVideo').html(numRequires);
					$('#videoDesc').val($.parseJSON(output).desc);
					$('#video-delete').show();							
					$('#edit-video').show();
					if ($.parseJSON(output).path != '') {	
						$('.fileinput-button').hide();
						$('#videoPath').show();
						$('#deleteExistingVideo').show();
					} else {
						// No physical video!
						$('.fileinput-button').show();
						$('#videoPath').hide();
						$('#deleteExistingVideo').hide();
					}

				}
			});
		});
		
		
		// ----------- Delete Video from Video/Activity Tile -----------------
		$('#deleteExistingVideo').click(function() {
			var videoID = $('#videoID').val();
			var confirmation = confirm('Are you sure you want to delete this video?');
			if (confirmation) {
				$.ajax({
					url: 'includes/delete_physical_video.php',
					data: {id: videoID},
					type: 'post',
					success: function(output) {
						// Resets and hides videoPath and close button
						$('#videoPath').val('');
						$('#videoPath').hide();
						$('#deleteExistingVideo').hide();
						$('.fileinput-button').show();
					}
				});
			}
		});
		
		// ----------- Handles New Video Change -----------------
		$('#fileupload').change(function() {
			$('#videoPathEdited').val("1");
		});
		
		
		//  Triggered when existing menu tile is selected. (Edit Menu)
		$('.preview-menu-item').click(function() {
			var pathToElement = $(this).data("path");
			var pathID = $(this).data("id");
			$.ajax({
				url: 'includes/get_menu.php',
				data: {path: pathToElement, id: pathID},
				type: 'post',
				success: function(output) {
					console.log(output);
					$('#menuName').val($.parseJSON(output).name);
					$('#menuID').val($.parseJSON(output).id);
					$('#requiresMenuPreview').val($.parseJSON(output).requires);
					
					$('input[type="checkbox"]').prop('checked', false); // Reset checkbox
					var childArray = $.parseJSON(output).requires.split(',');
					config.requiresArray = childArray;
					var numRequires = 0;
					for (var i=0;i<childArray.length;i++) {
						if (childArray[i] != "") {
							document.getElementById('cb-' + childArray[i]).checked = true;
							numRequires++;
						}
					}
					
					$('#numberOfRequiresMenu').html(numRequires);
					$('#menuRequires').val($.parseJSON(output).requires).attr('selected',true);
					$('#menuDesc').val($.parseJSON(output).desc);
					$('#menu-delete').show();			
					$('#edit-menu').show();
				}
			});
		});
		
		
		// Save changes
		$('#video-save').click(function() {
			var videoID = $('#videoID').val();
			var videoType = $('#videoType').val();
			var videoPath = $('#videoPath').val();
			var videoPathEdited = $('#videoPathEdited').val();
			var videoParentID = $('#videoParentID').val();
			var videoDataPath = $('#video-datapath').val();
			var videoName = $('#videoName').val();
			var videoRequires = config.requiresArray.toString();
			var videoDesc = $('#videoDesc').val();
			
			if (videoType == 0) {
				// Video Element
				if(videoID !== "") {
					// Editing element
					$.ajax({
						url: 'includes/edit_video.php',
						data: {id: videoID, datapath: videoDataPath, path: videoPath, pathEdited: videoPathEdited, name: videoName, requires: videoRequires, desc: videoDesc},
						type: 'post',
						success: function(output) {
							console.log(output);
							location.reload(true);
						}
					});
				} else {
					// Adding new element
					$.ajax({
						url: 'includes/add_video.php',
						data: {parentid: videoParentID, datapath: videoDataPath, path: videoPath, name: videoName, requires: videoRequires, desc: videoDesc},
						type: 'post',
						success: function(output) {
							location.reload(true);
						}
					});
				}
			} else {
				// Activity Element
				if(videoDataPath === "") {
					// Editing element
					$.ajax({
						url: 'includes/edit_activity.php',
						data: {id: videoID, datapath: videoDataPath, path: videoPath, name: videoName, requires: videoRequires, desc: videoDesc},
						type: 'post',
						success: function(output) {
							console.log(output);
							location.reload(true);
						}
					});
				} else {
					// Adding new element
					$.ajax({
						url: 'includes/add_activity.php',
						data: {parentid: videoParentID, datapath: videoDataPath, path: videoPath, name: videoName, requires: videoRequires, desc: videoDesc},
						type: 'post',
						success: function(output) {
							console.log(output);
							location.reload(true);
						}
					});
				}
			}
		});
		
		
		$('#menu-save').click(function() {
			var menuID = $('#menuID').val();
			var menuParentID = $('#menuParentID').val();
			var menuDataPath = $('#menu-datapath').val();
			var menuName = $('#menuName').val();
			var menuRequires = config.requiresArray.toString();
			var menuDesc = $('#menuDesc').val();
			
			if(menuID !== "") {
				// Editing element
				$.ajax({
					url: 'includes/edit_menu.php',
					data: {id: menuID, name: menuName, path: menuDataPath, requires: menuRequires, desc: menuDesc},
					type: 'post',
					success: function(output) {
						console.log(output);
						location.reload(true);
					}
				});
			} else {
				// Adding new element
				$.ajax({
					url: 'includes/add_menu.php',
					data: {parentid: menuParentID, name: menuName, path: menuDataPath, requires: menuRequires, desc: menuDesc},
					type: 'post',
					success: function(output) {
						console.log(output);
						location.reload(true);
					}
				});
			}
		});		
		 
		
		
		// Delete Nodes
		$('#video-delete').click(function() {
			var videoID = $('#videoID').val();
			$.ajax({
				url: 'includes/delete_video.php',
				data: {id: videoID},
				type: 'post',
				success: function(output) {
					console.log(output);
					location.reload(true);
				}
			});
		});
		$('#menu-delete').click(function() {
			var menuID = $('#menuID').val();
			$.ajax({
				url: 'includes/delete_menu.php',
				data: {id: menuID},
				type: 'post',
				success: function(output) {
					console.log(output);
					if (output == 'error') {
						$('body').prepend('<div id="delete-folder-error" class="alert alert-error alert-block" style="z-index: 10001;position: fixed;left: 50%;right:50%;width:500px;margin-left:-250px;"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> The folder you are trying to delete contains other videos/folders. For security purposes, delete all sub components before deleting this folder.</div>');
					} else {
						location.reload(true);
					}
				}
			});
		});
		
	},
	
	addToRequiresArray = function(elementToAdd) {
		if (jQuery.inArray(elementToAdd, config.requiresArray) == -1) {
			config.requiresArray.push(elementToAdd);
		}
	},
	
	addToRequires = function(elementToAdd, elementToAddName) {
		if (jQuery.inArray(elementToAdd.replace("cb-",''), config.requiresArray) == -1) {
			config.requiresArray.push(elementToAdd.replace("cb-",''));
			$('#requires-list').val(config.requiresArray);
			$('#requires-num-items').text('(' + config.requiresArray.length + ' items)');
		}
	},
		
	removeFromRequires = function(elementToRemove) {
		var index = config.requiresArray.indexOf(elementToRemove.replace("cb-",''));
		config.requiresArray.splice(index, 1);
		$('#requires-list').val(config.requiresArray);
		$('#requires-num-items').text('(' + config.requiresArray.length + ' items)');
	},
	checkChildren = function(parent) {
		var $parentCheck = $('#' + parent);
		if ($parentCheck.is(':checked')) {
			$parentCheck.parent().parent().find('input').each(function(){
				$(this).prop('checked', true);
				addToRequires($(this).attr("id"));
			});
		} else {
			$parentCheck.parent().parent().find('input').each(function(){
				$(this).prop('checked', false);
				removeFromRequires($(this).attr("id"));
			});
		}
	}

	return {
		init : init,
		addToRequiresArray : addToRequiresArray,
		checkChildren : checkChildren
	};

})(jQuery);
