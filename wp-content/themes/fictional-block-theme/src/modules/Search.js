
import $ from 'jquery'

class Search {
    constructor() {
        this.addSearchHTML();
        this.resultDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term")
        this.events();
        this.isOverlayActive = false;
        this.typingTimer = null;
        this.isSpinerVisible = false;
        this.previusValue = null;
    }

    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    typingLogic() {
        if (this.searchField.val() !== this.previusValue) {
            clearTimeout(this.typingTimer);
            if (this.searchField.val()) {
                if (!this.isSpinerVisible) {
                    this.resultDiv.html("<div class='spinner-loader'> </div>");
                    this.isSpinerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 750)

            } else {
                this.resultDiv.html("");
                this.isSpinerVisible = false;
            }

        }


        this.previusValue = this.searchField.val();
    }

    getResults() {
        let searchQuery = this.searchField.val();
        const rootUrl = universityData.root_url;
        $.getJSON(`${rootUrl}/wp-json/university/v1/search?term=${searchQuery}`, (results) => {
            this.resultDiv.html(`
            <div class="row">

                <div class="one-third">
                    <h2 class="search-overlay__section-title"> General Search Results </h2>
                    ${results.generalInfo.length ? '<ul class="link-list min-list">' : "No results found"}
                             ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a>
                             ${item.type === "post" ? `by ${item.authorName}` : ""}</li>`).join("")}
                    ${results.generalInfo.length ? '</ul>' : ""}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Programs </h2>
                    ${results.programs.length ? '<ul class="link-list min-list">' : "No results found"}
                             ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
                    ${results.programs.length ? '</ul>' : ""}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Campuses </h2>
                    ${results.campuses.length ? '<ul class="link-list min-list">' : "No results found"}
                             ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
                    ${results.campuses.length ? '</ul>' : ""}

                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Professors </h2>
                    ${results.professors.length ? '<ul class="professor-cards">' : "No results found"}
                             ${results.professors.map(item => `
                             <li class="professor-card__list-item">
                                <a class="professor-card" href="${item.permalink}">
                                    <img class="professor-card__image" src="${item.image}">
                                    <span class="professor-card__name">${item.title}</span>
                                </a>
                             </li>
                             `).join("")}
                    ${results.professors.length ? '</ul>' : ""}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Events </h2>
                    ${results.events.length ? '' : "No results found"}
                             ${results.events.map(item => `
                             <div class="event-summary">
                                    <a class="event-summary__date event-summary__date t-center" href="${item.permalink}">
                                        <span class="event-summary__month">
                                            ${item.month}
                                        </span>
                                        <span class="event-summary__day">
                                            ${item.month}
                                        </span>
                                    </a>
                                    <div class="event-summary__content">
                                        <h5 class="event-summary__title headline headline--tiny">
                                            <a href="${item.permalink}">
                                                ${item.title}
                                            </a>
                                        </h5>
                                        <p>
                                            ${item.excerpt}
                                            <a href="${item.permalink}" class="nu gray">Read more</a>
                                        </p>
                                    </div>
                             </div>

                     `).join("")}

                </div>


            </div>
            `);
            this.isSpinerVisible = false;

        });
        // for common query across the api rest WP
        // const searchQuery = this.searchField.val();
        // const rootUrl = universityData.root_url;
        // $.when(
        //     $.getJSON(`${rootUrl}/wp-json/wp/v2/posts?search=${searchQuery}`),
        //     $.getJSON(`${rootUrl}/wp-json/wp/v2/pages?search=${searchQuery}`)
        // ).then(
        //     (posts, pages) => {
        //         let combinedResult = posts[0].concat(pages[0]);
        //         this.resultDiv.html(`
        //                     <h2 class="search-overlay__section-title"> General Search Results </h2>
        //                      ${combinedResult.length ? '<ul class="link-list min-list">' : "No results found"}
        //                         ${combinedResult.map(item => `<li><a href="${item.link}">${item.title.rendered}</a>
        //                         ${item.type === "post" ? `by ${item.authorName}` : ""}
        //                         </li>`).join("")}
        //                      ${combinedResult.length ? '</ul>' : ""}
        //                     `);
        //         this.isSpinerVisible = false;
        //
        //     },
        //     () => {
        //         this.resultDiv.html(`<p>Unexpected error, please try again </p>`);
        //     }
        // );

    }

    openOverlay() {
        this.searchOverlay.addClass('search-overlay--active');
        $("body").addClass("body-no-scroll");
        setTimeout(() => this.searchField.focus(), 301)
        this.searchField.val('')
        this.isOverlayActive = true;
        return false;
    }

    closeOverlay() {
        this.searchOverlay.removeClass('search-overlay--active');
        $("body").removeClass("body-no-scroll");
        this.isOverlayActive = false;
    }

    keyPressDispatcher(e) {
        if (e.keyCode === 83 && !this.isOverlayActive && !$("input, textarea").is(":focus")) {
            this.openOverlay();
        }
        if (e.keyCode === 27 && this.isOverlayActive) {
            this.closeOverlay();
        }

    }

    addSearchHTML() {
        $('body').append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term"/>
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div id="search-overlay__results"></div>
                </div>
            </div>`)
    }
}

export default Search;

