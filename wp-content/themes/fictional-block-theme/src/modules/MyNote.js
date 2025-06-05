import $ from 'jquery';

class MyNote {
    constructor() {
        this.events();
    };

    events() {
        const myNoteSelector = $("#my-note");

        const noteEventHandlers = [
            {selector: ".delete-note", handler: this.deleteNote},
            {selector: ".edit-note", handler: this.editNote.bind(this)},
            {selector: ".update-note", handler: this.updateNote.bind(this)}
        ];

        noteEventHandlers.forEach(({selector, handler}) => {
            myNoteSelector.on("click", selector, handler);
        });

        $("#create-note").on("click", this.createNote.bind(this));
    }

    editNote(e) {
        const thisNote = $(e.target).parents("li");
        if (thisNote.data("state") === "editable") {
            this.makeNoteReadOnly(thisNote);
        } else {
            this.makeNoteEditable(thisNote);
        }

    };

    makeNoteEditable(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");
    };

    makeNoteReadOnly(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i>Edit');
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.data("state", "cancel");
    }


    // Methods will go here
    deleteNote(e) {
        const thisNote = $(e.target).parents("li")
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/${thisNote.data('id')}`,
            type: "DELETE",

            success: (response) => {
                thisNote.slideUp();
                console.log("congratulations");
                console.log(response);
                if (response.userNoteCount<5){
                    $(".note-limit-message").removeClass("active")

                }
            },
            error: (response) => {
                console.log(response);
                console.log("error");

            }
        })
    }

    updateNote(e) {
        const thisNote = $(e.target).parents("li")
        const ourUpdate = {
            "title": thisNote.find(".note-title-field").val(),
            "content": thisNote.find(".note-body-field").val()
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/${thisNote.data('id')}`,
            type: "POST",
            data: ourUpdate,

            success: (response) => {
                this.makeNoteReadOnly(thisNote);
                console.log("congratulations");
                console.log(response);
            },
            error: (response) => {
                console.log(response);
                console.log("error");
                console.log(response.url);
            }
        })
    }

    createNote()  {
        const ourNewPost = {
            "title": $(".new-note-title").val(),
            "content": $(".new-note-body").val(),
            "status": "publish",

        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/`,
            type: "POST",
            data: ourNewPost,

            success: (response) => {
                $(".new-note-title, .new-note-body").val("");
                $(`
                    <li data-id="${response.id}">
                    <label>
                        <input readonly value="${response.title.raw}" class="note-title-field">
                    </label>
                    <span class="edit-note">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        Edit
                    </span>

                    <span class="delete-note"> <i class="fa fa-trash-o" aria-hidden="true"></i>
                         Delete
                    </span>

                    <label>
                        <textarea readonly class="note-body-field">
                            ${response.content.raw}
                        </textarea>
                    </label>

                    <span class="update-note btn btn--blue btn--small">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        Save
                    </span>
                </li>
                    `).prependTo("#my-note").hide().slideDown();
                console.log(response);
                console.log("Congratulations");
            },
            error: (response) => {
                if (response.responseText === "You have reached your limit of 5 notes") {
                    $(".note-limit-message").addClass("active")

                }
                console.log(response);
                console.log("error");
            }
        })
    }

}


export default MyNote