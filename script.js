function enableButton() {
    var commentText = document.getElementById('commentText').value.trim();
    var mediaInput = document.getElementById('mediaInput').files.length > 0;
    var submitButton = document.getElementById('submitButton');
    if (commentText !== '' || mediaInput) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}

document.getElementById('mediaInput').addEventListener('change', enableButton);


function openEditModal(commentId) {
    var commentText = document.getElementById('commentText_' + commentId).innerText;
    document.getElementById('editCommentId').value = commentId;
    document.getElementById('editCommentText').value = commentText;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function saveEdit() {
    var commentId = document.getElementById('editCommentId').value;
    var newText = document.getElementById('editCommentText').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
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
    document.getElementById('replyParentId').value = commentId;
    document.getElementById('replyModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function closeModal() {
    document.getElementById('replyModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

  
function submitReply() {
      var replyText = document.getElementById('replyText').value;
      // Implement the logic to submit the reply
      console.log("Reply submitted: " + replyText);
      closeModal();
}

  


  // Get references to modal and overlay elements
var modal = document.getElementById('myModal');
var overlay = document.getElementById('overlay');

  // Get references to open and close modal buttons
var openModalBtn = document.getElementById('openModalBtn');
var closeModalBtn = document.getElementById('closeModalBtn');

 // Function to open the modal
 // function openModal() {
 //   modal.style.display = 'block';
 //   overlay.style.display = 'block';
 // }

 // Function to close the modal
 // function closeModal() {
 //   modal.style.display = 'none';
 //   overlay.style.display = 'none';
 // }

  // Attach click event listeners to open and close modal buttons
// openModalBtn.addEventListener('click', openModal);
// closeModalBtn.addEventListener('click', closeModal);



document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.repost-btn').forEach(button => {
        button.addEventListener('click', function() {
            const contentId = this.getAttribute('data-content-id');
            handleRepost(contentId, this);
        });
    });
});

function handleRepost(contentId, button) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "repost.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Response received: ", xhr.responseText);
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
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
    var repostedContent = document.createElement('div');
    repostedContent.classList.add('post');
    repostedContent.setAttribute('data-post-id', contentId);

    repostedContent.innerHTML = `
        <p><strong>${currentUser}</strong> (Reposted from ${originalUser}):</p>
        <p>${content}</p>
        <p>Reposted on: ${repostDate}</p>
    `;

    // Check if the reposted content already exists to avoid duplication
    var existingPost = document.querySelector('.reposted-content[data-post-id="' + contentId + '"]');
    if (existingPost) {
        existingPost.remove();
    }

    button.closest('.content-container').appendChild(repostedContent);
}







function confirmDelete(commentId) {
  if (confirm("Vai tiešām vēlaties dzēst šo komentāru?")) {
    // User clicked "OK"
    deleteComment(commentId);
  } else {
    // User clicked "Cancel"
    // Do nothing
  }
}

function deleteComment(commentId) {
  // AJAX request to delete the comment
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "delete_comment.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Response received
      var response = JSON.parse(xhr.responseText);
      if (response.success) {
        // Comment deleted successfully
        // Optionally, you can update the UI to reflect the deletion
        location.reload(); // Refresh the page
      } else {
        // Error deleting the comment
        alert("Radās kļūda dzēšot komentāru");
      }
    }
  };
  xhr.send("comment_id=" + commentId);
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.delete-reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const replyId = this.getAttribute('data-reply-id');
            deleteReply(replyId, this);
        });
    });
});

function deleteReply(replyId, button) {
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



// JavaScript code to handle like/unlike functionality and update the UI

// Function to handle like/unlike action
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
                        button.classList.add('liked'); // Add a class to change the color
                    } else if (response.action === 'atcelt patīk') {
                        button.classList.remove('liked'); // Remove the class to revert the color
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
    loadNotifications();
});

function loadNotifications() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_notifications.php');
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var notifications = JSON.parse(xhr.responseText);
                var notificationContainer = document.getElementById('notification-container');
                notificationContainer.innerHTML = ''; // Clear existing notifications
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







    // JavaScript to open a new tab when a comment link is clicked
function openCommentsTab(commentId) {
    window.open('view_comments.php?comment_id=' + commentId, '_blank');
}


function validateAndSubmit() {
    var commentText = document.getElementById('commentText').value.trim();
    if (commentText === '' && !document.getElementById('mediaInput').files.length) {
        alert('Comment text cannot be empty');
        return false;
    } else {
        document.getElementById('commentForm').submit();
    }
}



function openReplyForm(commentId) {
  document.getElementById('parent_comment_id').value = commentId;
  document.getElementById('teksts').focus();
}


  // Define a function to show a modal for editing the comment text
function showModal(commentText) {
    // Here, you can implement your modal logic to show a modal with an input field for editing
    // For example, you can create a modal dialog using a library like Bootstrap or create a custom modal

    // For demonstration purposes, let's alert the comment text
    var editedCommentText = prompt("Edit the comment:", commentText);
    return editedCommentText;
}



function redirectToProfile(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'profile.php?username=' + encodeURIComponent(username);
}

function redirectToSettings(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'settings.php';
}
function redirectToGames(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'speles.php';
}

function redirectToNoti(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'notification.php';
}

function redirectToChat(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'messenger.php';
}
function redirectToStart(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'index.php';
}


function validateForm() {
    var commentText = document.getElementById('commentText').value.trim();
    if (commentText === '') {
        alert('Comment text cannot be empty');
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}


          


document.addEventListener('DOMContentLoaded', function() {
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