document.addEventListener("DOMContentLoaded", function () {
    const commentForm = document.getElementById("commentform");
    if (!commentForm) return;
  
    const commentParentInput = commentForm.querySelector('input[name="comment_parent"]');
  
    commentForm.addEventListener("submit", function (e) {
      e.preventDefault();
  
      const formData = new FormData(commentForm);
      formData.append("nonce", ajaxComments.nonce);
      formData.append("action", "submit_comment");
  
      const submitButton = commentForm.querySelector('input[type="submit"]');
      submitButton.disabled = true;
      submitButton.value = "Submitting...";
  
      fetch(ajaxComments.ajax_url, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("AJAX response:", data);
          submitButton.disabled = false;
          submitButton.value = "Post Comment";
  
          let msgDiv = document.querySelector(".comment-message");
          if (!msgDiv) {
            msgDiv = document.createElement("div");
            msgDiv.className = "comment-message";
            commentForm.prepend(msgDiv);
          }
  
          if (data.success) {
            msgDiv.innerText = ajaxComments.messages.success;
            msgDiv.style.color = "green";
  
            const parentID = data.data.parent;
            const commentList = document.querySelector('.comment-list, #comments > ol, #comments > ul');
  
            if (parentID && parentID !== 0) {
              const parentComment = document.getElementById(`comment-${parentID}`);
              if (parentComment) {
                let childrenList = parentComment.querySelector('.children');
                if (!childrenList) {
                  childrenList = document.createElement('ul');
                  childrenList.classList.add('children');
                  parentComment.appendChild(childrenList);
                }
                childrenList.insertAdjacentHTML('beforeend', data.data.comment);
              }
            } else if (commentList) {
              commentList.insertAdjacentHTML('beforeend', data.data.comment);
            }
  
            if (commentList) {
              commentList.after(commentForm.parentNode);
            }
  
            const countEl = document.querySelector(".comments-title, .comment-count");
            if (countEl) {
              countEl.innerText = `Comments (${data.data.comment_count})`;
            }
  
            commentForm.reset();
            if (commentParentInput) commentParentInput.value = 0;
          } else {
            msgDiv.innerText = data.data?.message || ajaxComments.messages.error;
            msgDiv.style.color = "red";
          }
        })
        .catch((err) => {
          submitButton.disabled = false;
          submitButton.value = "Post Comment";
          alert("AJAX error. Please try again.");
          console.error(err);
        });
    });
  });
  