document.addEventListener("DOMContentLoaded", () => {
	let addFolderForm = document.getElementById('addNewFolderForm');

	function handleAddFolderForm(event) {
		event.preventDefault(); // Empêche la page de se rafraîchir après le submit du formulaire

		console.log(event)

		let name = document.getElementById('addNewFolderFormInput').value
		let isEditable = document.getElementById('isEditable').checked
		let isDeletable = document.getElementById('isDeletable').checked
		let isReadable = document.getElementById('isRedable').checked

		$.ajax({
			url: '/drive/create-directory/' + folderId,
			method: 'POST',
			data: {
				"name": name,
                "isEditable": isEditable,
				"isDeletable": isDeletable,
				"isReadable": isReadable
			},
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
			}
		}).done(function (responseData) {
			console.log(responseData);
			window.location.href = '/drive/' + folderId;

		}).fail(function (error) {
			console.log(error);
		});

	}
	addFolderForm.addEventListener('submit', handleAddFolderForm);

	let fileForm = document.getElementById('addFileForm');
	function handleFileForm(event) {
		event.preventDefault(); // Empêche la page de se rafraîchir après le submit du formulaire

		console.log(event)

		let file = document.getElementById('addFileFormInput').files[0];

		const data = new FormData();
		data.append('file', file);

		$.ajax({
			url: '/drive/add-file/' + folderId,
			method: 'POST',
			data: data,
			processData: false,
			contentType: false,
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
			}
		}).done(function (responseData) {
			console.log(responseData);
			window.location.href = '/drive/' + folderId;

		}).fail(function (error) {
			console.log(error);
		});

	}
	fileForm.addEventListener('submit', handleFileForm);

	$('.remove-item').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
		//Avoir le parent qui pour class card
		let parent = $(this).parents('.card');
		let id = parent.attr('data-id');
		let type = parent.attr('data-type');


		var url = '';

		if (type == 'folder') {
			url = '/drive/delete-directory/' + id;
		} else {
			url = '/drive/delete-file/' + id;
		}

		$.ajax({
			url: url,
			method: 'POST',
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
			}
		}).done(function (responseData) {
			console.log(responseData);
			window.location.href = '/drive/' + folderId;
		}).fail(function (error) {
			console.log(error);
		});
	});

    //Rename folder
    $('.rename-item').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        //Avoir le parent qui pour class card
        let parent = $(this).parents('.card');
        let id = parent.attr('data-id');

        $("#addNewFolderFormId").val(id);
    });

    let renameFolderForm = document.getElementById('renameFolderForm');

	function handleRenameFolderForm(event) {
		event.preventDefault(); // Empêche la page de se rafraîchir après le submit du formulaire

		let name = document.getElementById('renameFolderFormInput').value
        let id = document.getElementById('addNewFolderFormId').value

		$.ajax({
			url: '/drive/rename-directory/' + id,
			method: 'POST',
			data: {
				"name": name,
			},
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
			}
		}).done(function (responseData) {
			console.log(responseData);
			window.location.href = '/drive/' + folderId;

		}).fail(function (error) {
			console.log(error);
		});

	}
	renameFolderForm.addEventListener('submit', handleRenameFolderForm);

    //Change permissions 
    

    $('.permissions-folder-item').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        //Avoir le parent qui pour class card
        let parent = $(this).parents('.card');
        let id = parent.attr('data-id');

        $.ajax({
			url: '/drive/get-directory-permissions/' + id,
			method: 'GET',
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
			}
		}).done(function (responseData) {
			console.log(responseData);
            $("#editPermissionsFolderFormId").val(id);
            $("#isEditablePermissions").prop('checked', responseData.isEditable);
            $("#isDeletablePermissions").prop('checked', responseData.isDeletable);
            $("#isRedablePermissions").prop('checked', responseData.isReadable);

		}).fail(function (error) {
			console.log(error);
		});
    });

    let changePermissionsFolderForm = document.getElementById('editPermissionsFolderForm');

	function handleChangePermissionsFolderForm(event) {
		event.preventDefault(); // Empêche la page de se rafraîchir après le submit du formulaire

        let id = document.getElementById('editPermissionsFolderFormId').value
        let isEditable = document.getElementById('isEditablePermissions').checked
		let isDeletable = document.getElementById('isDeletablePermissions').checked
		let isReadable = document.getElementById('isRedablePermissions').checked

        console.log(isEditable, isDeletable, isReadable)

		$.ajax({
			url: '/drive/edit-directory-permissions/' + id,
			method: 'POST',
			data: {
                "isEditable": isEditable,
				"isDeletable": isDeletable,
				"isReadable": isReadable
			},
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
			}
		}).done(function (responseData) {
			console.log(responseData);
			window.location.href = '/drive/' + folderId;

		}).fail(function (error) {
			console.log(error);
		});

	}
	changePermissionsFolderForm.addEventListener('submit', handleChangePermissionsFolderForm);

    //Télécharger un fichier
    $('.download-item').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        //Avoir le parent qui pour class card
        let parent = $(this).parents('.card');
        let id = parent.attr('data-id');

        window.location.href = '/drive/download-file/' + id;
    });

});
