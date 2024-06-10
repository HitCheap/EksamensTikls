$(document).ready(function() {
    // Edit message functionality
    $('.messages-list').on('click', '.edit-button', function() {
        var li = $(this).closest('li');
        var messageId = li.data('message-id');
        var messageContent = li.find('.message-content').text();
        var editInput = $('<input type="text" class="edit-input" value="' + messageContent + '">');
        li.find('.message-content').html(editInput);
        li.find('.edit-button, .delete-button').hide();
        li.find('.save-button, .cancel-button').show();
    });

    // Save edited message
    $('.messages-list').on('click', '.save-button', function() {
        var li = $(this).closest('li');
        var messageId = li.data('message-id');
        var newMessageContent = li.find('.edit-input').val().trim();
        
        // Send AJAX request to update message content in database
        $.ajax({
            type: 'POST',
            url: 'update_message.php',
            data: { message_id: messageId, content: newMessageContent },
            success: function(response) {
                if (response === 'Message updated successfully') {
                    li.find('.message-content').html(newMessageContent + '<em>' + li.find('em').text() + '</em>');
                    li.find('.edit-button, .delete-button').show();
                    li.find('.save-button, .cancel-button').hide();
                } else {
                    alert('Error updating message');
                }
            }
        });
    });

    // Cancel edit message
    $('.messages-list').on('click', '.cancel-button', function() {
        var li = $(this).closest('li');
        var originalContent = li.find('.edit-input').val();
        li.find('.message-content').html(originalContent + '<em>' + li.find('em').text() + '</em>');
        li.find('.edit-button, .delete-button').show();
        li.find('.save-button, .cancel-button').hide();
    });

    // Delete message functionality
    $('.messages-list').on('click', '.delete-button', function() {
        var messageId = $(this).closest('li').data('message-id');
        if (confirm('Are you sure you want to delete this message?')) {
            // Send AJAX request to delete message from database
            $.ajax({
                type: 'POST',
                url: 'delete_message.php',
                data: { message_id: messageId },
                success: function(response) {
                    if (response === 'Message deleted successfully') {
                        location.reload();
                    } else {
                        alert('Error deleting message');
                    }
                }
            });
        }
    });
});
