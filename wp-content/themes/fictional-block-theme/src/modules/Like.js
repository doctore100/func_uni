import $ from 'jquery';

class Like {
    constructor() {
        this.events();


    }

    events() {
        $(".like-box").on("click", this.onClickDispatcher.bind(this))
    }

    onClickDispatcher(e) {
        const currentLikeBox = $(e.target).closest(".like-box");
        currentLikeBox.attr("data-exists") === "yes" ? this.deleteLike(currentLikeBox) : this.createLike(currentLikeBox);
    }

    createLike(currentLikeBox) {

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },

            url: `${universityData.root_url}/wp-json/university/v1/manageLike`,
            type: "POST",
            data: {
                'professorID': currentLikeBox.data("professor"),
            },
            success: (response) => {
                currentLikeBox.attr("data-exists", "yes");
                const likeCountElement = currentLikeBox.find(".like-count");
                let likeCount = parseInt(likeCountElement.html(), 10);
                likeCount++;
                likeCountElement.html(likeCount);
                currentLikeBox.attr("data-like", response.likeID);
                console.log(response);
            },
            error: (error) => {

                console.log(error);
            }
        })

    }

    deleteLike(currentLikeBox) {
        const likeElementId = currentLikeBox.attr("data-like");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },

            url: `${universityData.root_url}/wp-json/university/v1/manageLike`,
            data: {
                'like': likeElementId
            },
            type: "DELETE",

            success: (response) => {
                currentLikeBox.attr("data-exists", "no");
                const likeCountElement = currentLikeBox.find(".like-count");
                let likeCount = parseInt(likeCountElement.html(), 10);
                likeCount--;
                likeCountElement.html(likeCount);
                currentLikeBox.attr("data-like", '');
                console.log("success deleted like");

                console.log(response);
            },
            error: (error) => {
                console.log("error deleted like");

                console.log(error);
            }
        })

    }
}

export default Like;