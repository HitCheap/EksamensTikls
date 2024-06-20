$(document).ready(function() {
    // Rediģēt ziņojumu funkcionalitāte
    $('.messages-list').on('click', '.edit-button', function() {
        var li = $(this).closest('li'); // Atrodam tuvāko <li> elementu
        var messageId = li.data('message-id'); // Iegūstam ziņojuma ID
        var messageContent = li.find('.message-content').text(); // Iegūstam ziņojuma saturu
        var editInput = $('<input type="text" class="edit-input" value="' + messageContent + '">'); // Izveidojam rediģēšanas ievadi
        li.find('.message-content').html(editInput); // Aizvietojam ziņojuma saturu ar ievadi
        li.find('.edit-button, .delete-button').hide(); // Slēpjam rediģēšanas un dzēšanas pogas
        li.find('.save-button, .cancel-button').show(); // Rādām saglabāšanas un atcelšanas pogas
    });

    // Saglabāt rediģēto ziņojumu
    $('.messages-list').on('click', '.save-button', function() {
        var li = $(this).closest('li'); // Atrodam tuvāko <li> elementu
        var messageId = li.data('message-id'); // Iegūstam ziņojuma ID
        var newMessageContent = li.find('.edit-input').val().trim(); // Iegūstam jauno ziņojuma saturu
        
        // Sūtam AJAX pieprasījumu, lai atjauninātu ziņojuma saturu datubāzē
        $.ajax({
            type: 'POST',
            url: 'update_message.php', // PHP fails, kas apstrādā ziņojuma atjaunināšanu
            data: { message_id: messageId, content: newMessageContent }, // Sūtam ziņojuma ID un jauno saturu
            success: function(response) {
                if (response === 'Message updated successfully') { // Ja atjaunināšana veiksmīga
                    li.find('.message-content').html(newMessageContent + '<em>' + li.find('em').text() + '</em>'); // Atjaunojam ziņojuma saturu HTML
                    li.find('.edit-button, .delete-button').show(); // Rādām rediģēšanas un dzēšanas pogas
                    li.find('.save-button, .cancel-button').hide(); // Slēpjam saglabāšanas un atcelšanas pogas
                } else {
                    alert('Error updating message'); // Ja atjaunināšana neveiksmīga, rādām kļūdas ziņu
                }
            }
        });
    });

    // Atcelt rediģēšanu
    $('.messages-list').on('click', '.cancel-button', function() {
        var li = $(this).closest('li'); // Atrodam tuvāko <li> elementu
        var originalContent = li.find('.edit-input').val(); // Iegūstam oriģinālo ziņojuma saturu
        li.find('.message-content').html(originalContent + '<em>' + li.find('em').text() + '</em>'); // Atjaunojam ziņojuma saturu HTML
        li.find('.edit-button, .delete-button').show(); // Rādām rediģēšanas un dzēšanas pogas
        li.find('.save-button, .cancel-button').hide(); // Slēpjam saglabāšanas un atcelšanas pogas
    });

    // Dzēst ziņojumu funkcionalitāte
    $('.messages-list').on('click', '.delete-button', function() {
        var messageId = $(this).closest('li').data('message-id'); // Iegūstam ziņojuma ID
        if (confirm('Are you sure you want to delete this message?')) { // Apstiprinājuma dialogs
            // Sūtam AJAX pieprasījumu, lai dzēstu ziņojumu no datubāzes
            $.ajax({
                type: 'POST',
                url: 'delete_message.php', // PHP fails, kas apstrādā ziņojuma dzēšanu
                data: { message_id: messageId }, // Sūtam ziņojuma ID
                success: function(response) {
                    if (response === 'Message deleted successfully') { // Ja dzēšana veiksmīga
                        location.reload(); // Pārlādējam lapu, lai atjauninātu interfeisu
                    } else {
                        alert('Error deleting message'); // Ja dzēšana neveiksmīga, rādām kļūdas ziņu
                    }
                }
            });
        }
    });
});

$(document).ready(function() {
    $('.delete-conversation').on('click', function() {
        var conversationId = $(this).data('conversation-id'); // Iegūstam sarunas ID
        
        if (confirm('Are you sure you want to delete this conversation?')) { // Apstiprinājuma dialogs
            $.ajax({
                type: 'POST',
                url: 'delete_conversation.php', // PHP fails, kas apstrādā sarunas dzēšanu
                data: { 
                    delete_conversation: true,
                    conversation_id: conversationId // Sūtam sarunas ID
                },
                success: function() {
                    // Atjaunojam UI, lai atspoguļotu sarunas dzēšanu
                    $(this).closest('li').remove(); // Noņemam sarunu no sānjoslas
                    // Novirzīšana uz messenger.php pēc dzēšanas tiek apstrādāta PHP failā
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Konsolējam kļūdas ziņu
                    alert('Error deleting conversation.'); // Rādām kļūdas ziņu
                }
            });
        }
    });
});
