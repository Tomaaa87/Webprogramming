const parts = [
    { id: 1, name: "Brake Pad" },
    { id: 2, name: "Oil Filter" }
];

function loadTableData(parts) {
    const tableBody = $('#dataTable tbody');
    tableBody.empty();
    parts.forEach(part => {
        tableBody.append(`
            <tr>
                <td>${part.id}</td>
                <td>${part.name}</td>
                <td>
                    <button class="editBtn" data-id="${part.id}">Edit</button>
                    <button class="deleteBtn" data-id="${part.id}">Delete</button>
                </td>
            </tr>
        `);
    });

    $('.deleteBtn').click(function() {
        const id = $(this).data('id');
        deletePart(id);
    });

    $('.editBtn').click(function() {
        const id = $(this).data('id');
        editPart(id);
    });
}

function deletePart(id) {
    toastr.success(`Part with ID ${id} deleted successfully!`);
    parts.splice(parts.findIndex(part => part.id === id), 1);
    loadTableData(parts);
}

function editPart(id) {
    toastr.success(`Part with ID ${id} edited successfully!`);
   
}

function loadContent() {
    $.ajax({
        url: 'content.json',
        dataType: 'json',
        success: function(data) {
            const contentDiv = $('#content');
            data.forEach(item => {
                contentDiv.append(`
                    <h2>${item.title}</h2>
                    <p>${item.content}</p>
                `);
            });
        }
    });
}

$(document).ready(function() {
    loadTableData(parts);
    loadContent();

    $('#showSuccess').click(function() {
        toastr.success('This is a success message!');
    });

    $('#showError').click(function() {
        toastr.error('This is an error message!');
    });

    
});
