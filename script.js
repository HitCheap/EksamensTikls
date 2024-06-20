function enableButton() {
    // Iegūstam komentāra teksta vērtību un noņemam nevajadzīgas atstarpes
    var commentText = document.getElementById('commentText').value.trim();
    // Pārbaudām, vai ir pievienots kāds mediju fails
    var mediaInput = document.getElementById('mediaInput').files.length > 0;
    // Iegūstam pogas "submit" elementu
    var submitButton = document.getElementById('submitButton');
    // Ja komentāra teksts nav tukšs vai ir pievienots mediju fails, iespējojam pogu "submit"
    if (commentText !== '' || mediaInput) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}

// Pievienojam notikuma klausītāju, lai izsauktu enableButton funkciju, kad mainās mediju ievades laukums
document.getElementById('mediaInput').addEventListener('change', enableButton);

function openEditModal(commentId) {
    // Iegūstam rediģējamā komentāra tekstu
    var commentText = document.getElementById('commentText_' + commentId).innerText;
    // Uzstādām rediģējamā komentāra ID slēptajam laukam
    document.getElementById('editCommentId').value = commentId;
    // Uzstādām rediģējamā komentāra tekstu rediģēšanas laukam
    document.getElementById('editCommentText').value = commentText;
    // Rādām rediģēšanas modālo logu
    document.getElementById('editModal').style.display = 'block';
}

// Slēpjam rediģēšanas modālo logu
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function saveEdit() {
    // Iegūstam rediģējamā komentāra ID un jauno tekstu
    var commentId = document.getElementById('editCommentId').value;
    var newText = document.getElementById('editCommentText').value;

    // Izveidojam XMLHttpRequest objektu, lai nosūtītu AJAX pieprasījumu
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Atjauninām komentāra tekstu lapā
                    document.getElementById('commentText_' + commentId).innerText = newText;
                    var editedLabel = document.querySelector('#comment_' + commentId + ' .edited-label');
                    if (!editedLabel) {
                        var label = document.createElement('span');
                        label.className = 'edited-label';
                        label.innerText = '(Rediģēts)';
                        document.querySelector('#comment_' + commentId + ' .edit-btn').after(label);
                    }
                    closeEditModal();
                } else {
                    console.error(response.message);
                }
            } catch (e) {
                console.error("Invalid JSON response from server");
            }
        }
    };
    xhr.send("comment_id=" + encodeURIComponent(commentId) + "&new_text=" + encodeURIComponent(newText));
}

function openModal(commentId) {
    // Uzstādām atbildes vecāka komentāra ID slēptajam laukam
    document.getElementById('replyParentId').value = commentId;
    // Rādām atbildes modālo logu un pārklājumu
    document.getElementById('replyModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

// Slēpjam atbildes modālo logu un pārklājumu
function closeModal() {
    document.getElementById('replyModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function submitReply() {
    // Iegūstam atbildes tekstu un izvadām to konsolē
    var replyText = document.getElementById('replyText').value;
    console.log("Reply submitted: " + replyText);
    closeModal();
}

// Iegūstam modālo logu un pārklājuma elementu atsauces
var modal = document.getElementById('myModal');
var overlay = document.getElementById('overlay');

// Iegūstam atvērt un aizvērt modālo logu pogu atsauces
var openModalBtn = document.getElementById('openModalBtn');
var closeModalBtn = document.getElementById('closeModalBtn');

// Funkcija, lai atvērtu modālo logu (komentēta ārā, lai neatkārtotos)
 // function openModal() {
 //   modal.style.display = 'block';
 //   overlay.style.display = 'block';
 // }

// Funkcija, lai aizvērtu modālo logu (komentēta ārā, lai neatkārtotos)
 // function closeModal() {
 //   modal.style.display = 'none';
 //   overlay.style.display = 'none';
 // }

// Pievienojam notikuma klausītājus, lai atvērtu un aizvērtu modālo logu
// openModalBtn.addEventListener('click', openModal);
// closeModalBtn.addEventListener('click', closeModal);

document.addEventListener("DOMContentLoaded", function() {
    // Pievienojam notikuma klausītājus visām repost pogām
    document.querySelectorAll('.repost-btn').forEach(button => {
        button.addEventListener('click', function() {
            const contentId = this.getAttribute('data-content-id');
            handleRepost(contentId, this);
        });
    });
});

function handleRepost(contentId, button) {
    // Izveidojam XMLHttpRequest objektu, lai nosūtītu AJAX pieprasījumu
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "repost.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Response received: ", xhr.responseText);
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Atjauninām pogas tekstu atkarībā no pārpublicēšanas stāvokļa
                    button.textContent = response.isReposted ? "Atcelt pārpublicēšanu" : "Pārpublicēt";
                    if (response.isReposted && response.content) {
                        addRepostedContent(response.content, response.repost_date, response.current_user, response.original_user, contentId, button);
                    }
                } else {
                    console.error(response.message);
                }
            } catch (e) {
                console.error("Invalid JSON response from server: ", xhr.responseText);
            }
        }
    };
    xhr.send("content_id=" + encodeURIComponent(contentId));
}

function addRepostedContent(content, repostDate, currentUser, originalUser, contentId, button) {
    // Izveidojam jaunu elementu pārpublicētajam saturam
    var repostedContent = document.createElement('div');
    repostedContent.classList.add('post');
    repostedContent.setAttribute('data-post-id', contentId);

    repostedContent.innerHTML = `
        <p><strong>${currentUser}</strong> (Reposted from ${originalUser}):</p>
        <p>${content}</p>
        <p>Reposted on: ${repostDate}</p>
    `;

    // Pārbaudām, vai pārpublicētais saturs jau eksistē, lai izvairītos no dublikātiem
    var existingPost = document.querySelector('.reposted-content[data-post-id="' + contentId + '"]');
    if (existingPost) {
        existingPost.remove();
    }

    button.closest('.content-container').appendChild(repostedContent);
}

function confirmDelete(commentId) {
    if (confirm("Vai tiešām vēlaties dzēst šo komentāru?")) {
        // Lietotājs noklikšķināja "OK"
        deleteComment(commentId);
    } else {
        // Lietotājs noklikšķināja "Cancel"
        // Nekas netiek darīts
    }
}

function deleteComment(commentId) {
    // Izveidojam XMLHttpRequest objektu, lai nosūtītu AJAX pieprasījumu
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete_comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Saņemam atbildi
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Komentārs veiksmīgi dzēsts
                // Varam atjaunināt UI, lai atspoguļotu dzēšanu
                location.reload(); // Atsvaidzinām lapu
            } else {
                // Kļūda dzēšot komentāru
                alert("Radās kļūda dzēšot komentāru");
            }
        }
    };
    xhr.send("comment_id=" + commentId);
}

document.addEventListener("DOMContentLoaded", function() {
    // Pievienojam notikuma klausītājus visām dzēšanas pogām atbildēm
    document.querySelectorAll('.delete-reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const replyId = this.getAttribute('data-reply-id');
            deleteReply(replyId, this);
        });
    });
});

function deleteReply(replyId, button) {
    // Izveidojam XMLHttpRequest objektu, lai nosūtītu AJAX pieprasījumu
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete_reply.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Response received: ", xhr.responseText);
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    var replyElement = button.closest('.reply');
                    if (replyElement) {
                        replyElement.remove();
                    }
                } else {
                    console.error(response.message);
                }
            } catch (e) {
                console.error("Invalid JSON response from server: ", xhr.responseText);
            }
        }
    };
    xhr.send("reply_id=" + encodeURIComponent(replyId));
}

// JavaScript kods, lai pārvaldītu patīk/atceļ patīk funkcionalitāti un atjauninātu UI

// Funkcija, lai pārvaldītu patīk/atceļ patīk darbību
document.addEventListener('DOMContentLoaded', function() {
    var likeContainers = document.querySelectorAll('.like-container');
    likeContainers.forEach(function(container) {
        container.removeEventListener('click', handleLikeClick);
        container.addEventListener('click', handleLikeClick);
    });
});

function handleLikeClick(event) {
    if (event.target.classList.contains('like-btn')) {
        var button = event.target;
        var postID = button.closest('.like-container').getAttribute('data-post-id');
        console.log('Like button clicked, post ID:', postID);
        handleLike(postID, button);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var likedPosts = JSON.parse(localStorage.getItem('likedPosts')) || {};
    Object.keys(likedPosts).forEach(function(postId) {
        var likeButton = document.getElementById('likeButton_' + postId);
        if (likeButton) {
            likeButton.classList.add('liked');
            likeButton.textContent = 'Atcelt patīk';
        }
    });
});

function handleLike(postID, button) {
    // Izveidojam XMLHttpRequest objektu, lai nosūtītu AJAX pieprasījumu
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'like.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.error) {
                    console.error(response.error);
                } else {
                    var likeCount = document.getElementById('likeCount_' + postID);

                    if (response.action === 'patīk') {
                        button.classList.add('liked'); // Pievienojam klasi, lai mainītu krāsu
                    } else if (response.action === 'atcelt patīk') {
                        button.classList.remove('liked'); // Noņemam klasi, lai atgrieztu sākotnējo krāsu
                    }

                    likeCount.textContent = response.like_count;
                }
            } catch (e) {
                console.error("Invalid JSON response from server: " + xhr.responseText);
            }
        } else {
            console.error(xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send('post_id=' + encodeURIComponent(postID));
}

document.addEventListener('DOMContentLoaded', function() {
    // Ielādējam paziņojumus, kad lapa ir ielādēta
    loadNotifications();
});

function loadNotifications() {
    // Izveidojam XMLHttpRequest objektu, lai nosūtītu AJAX pieprasījumu
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_notifications.php');
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var notifications = JSON.parse(xhr.responseText);
                var notificationContainer = document.getElementById('notification-container');
                notificationContainer.innerHTML = ''; // Iztīrām esošos paziņojumus
                notifications.forEach(function(notification) {
                    var notificationElement = document.createElement('div');
                    notificationElement.classList.add('notification');
                    notificationElement.textContent = notification.message;
                    notificationContainer.appendChild(notificationElement);
                });
            } catch (e) {
                console.error("Invalid JSON response from server: " + xhr.responseText);
            }
        } else {
            console.error(xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send();
}

// JavaScript kods, lai atvērtu jaunu cilni, kad tiek noklikšķināta komentāra saite
function openCommentsTab(commentId) {
    window.open('view_comments.php?comment_id=' + commentId, '_blank');
}

function validateAndSubmit() {
    // Iegūstam komentāra teksta vērtību un noņemam nevajadzīgas atstarpes
    var commentText = document.getElementById('commentText').value.trim();
    if (commentText === '' && !document.getElementById('mediaInput').files.length) {
        // Ja komentāra teksts ir tukšs un nav pievienoti mediju faili, rādām brīdinājumu
        alert('Comment text cannot be empty');
        return false;
    } else {
        // Ja viss ir kārtībā, iesniedzam formu
        document.getElementById('commentForm').submit();
    }
}

function openReplyForm(commentId) {
    // Uzstādām vecāka komentāra ID slēptajam laukam un fokusējamies uz teksta lauku
    document.getElementById('parent_comment_id').value = commentId;
    document.getElementById('teksts').focus();
}

// Definējam funkciju, lai parādītu modālo logu komentāra rediģēšanai
function showModal(commentText) {
    // Šeit var implementēt modālo logu ar ievades lauku rediģēšanai
    var editedCommentText = prompt("Edit the comment:", commentText);
    return editedCommentText;
}

function redirectToProfile(username) {
    // Pāradresē lietotāju uz profila lapu, balstoties uz lietotājvārdu
    window.location.href = 'profile.php?username=' + encodeURIComponent(username);
}

function redirectToSettings(username) {
    // Pāradresē lietotāju uz iestatījumu lapu
    window.location.href = 'settings.php';
}

function redirectToGames(username) {
    // Pāradresē lietotāju uz spēļu lapu
    window.location.href = 'speles.php';
}

function redirectToNoti(username) {
    // Pāradresē lietotāju uz paziņojumu lapu
    window.location.href = 'notification.php';
}

function redirectToChat(username) {
    // Pāradresē lietotāju uz tērzēšanas lapu
    window.location.href = 'messenger.php';
}

function redirectToStart(username) {
    // Pāradresē lietotāju uz sākuma lapu
    window.location.href = 'index.php';
}

function validateForm() {
    // Iegūstam komentāra teksta vērtību un noņemam nevajadzīgas atstarpes
    var commentText = document.getElementById('commentText').value.trim();
    if (commentText === '') {
        // Ja komentāra teksts ir tukšs, rādām brīdinājumu un nepieļaujam formas iesniegšanu
        alert('Comment text cannot be empty');
        return false; // Novēršam formas iesniegšanu
    }
    return true; // Ļaujam formas iesniegšanu
}

document.addEventListener('DOMContentLoaded', function() {
    // Pievienojam notikuma klausītājus čata pogai un aizvēršanas pogai
    const chatButton = document.getElementById('chat-button');
    const chatWindow = document.getElementById('chat-window');
    const closeButton = document.getElementById('close-button');

    chatButton.addEventListener('click', function() {
        chatWindow.classList.toggle('hidden');
    });

    closeButton.addEventListener('click', function() {
        chatWindow.classList.add('hidden');
    });
});

function toggleReplies(commentId, e) {
    // Pārslēdzam atbilžu konteineru redzamību, pamatojoties uz noklikšķināto elementu
    var repliesContainer = document.getElementById('replies_' + commentId);
    if (e.target.id === 'comment_' + commentId) {
        if (localStorage.getItem('replies_' + commentId) === 'true') {
            repliesContainer.style.display = 'none';
            localStorage.setItem('replies_' + commentId, 'false');
        } else {
            repliesContainer.style.display = 'block';
            localStorage.setItem('replies_' + commentId, 'true');
        }
    }
}

window.onload = function() {
    // Saglabājam atbilžu konteineru sākotnējo stāvokli, kad lapa tiek ielādēta
    var commentContainers = document.querySelectorAll('.comment-container');
    for (var i = 0; i < commentContainers.length; i++) {
        var commentId = commentContainers[i].id.replace('comment_', '');
        localStorage.setItem('replies_' + commentId, 'false');
    }
}

    //        function enableButton() {
     //           const commentText = document.getElementById('commentText').value;
      //          document.getElementById('submitButton').disabled = commentText.trim() === '';
        //    }
    //
      //      function validateAndSubmit() {
        //        // Additional validation can be added here
          //      return true;
            //}

  //  function validateAndSubmit() {
  //      // Get the comment text value and trim any whitespace
  //      var commentText = document.getElementById('commentText').value.trim();
  //      
  //      // Check if the comment text is empty
  //      if (commentText === '') {
  //          // If empty, show an alert message to the user
  //          alert('Comment text cannot be empty');
  //      } else {
  //          // If not empty, submit the form
  //          document.getElementById('commentForm').submit();
  //      }
  //  }