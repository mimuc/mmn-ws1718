var apiKey = "35090d7629fb4f3890f101a00715b773";

$(document).ready(function () {
    getListOfNewsPortals();
});

function makeRequest(type, params, done, error) {
    $.get({
        url: "https://newsapi.org/v2/" + type + params,
        headers: {
            "X-Api-Key": apiKey
        }
    }).done(done).fail(error);
}

function getListOfNewsPortals() {
    makeRequest("sources", "?language=en", function (data) {
        generateHTMLListOfSources(data.sources)
    });
}

function loadNewsFeed(sourceId) {
    makeRequest("top-headlines", "?sources=" + sourceId, function (data) {
        generateHTMLNewsFeed(data.articles);
        console.log(data);
    }, function (error) {
        console.error(error);
    });
}

function generateHTMLListOfSources(sources) {
    console.log(sources);
    var sidebar = $("#sidebar");
    $(sources).each(function (index, source) {
        var img = $("<p></p>")
        img.addClass("source");
        img.attr("id", source.id);
        img.html(source.name);
        img.on("click", function () {
            loadNewsFeed($(this).attr("id"));
        });
        sidebar.append(img);
    })
}

function generateHTMLNewsFeed(articles) {
    console.log(articles);
    var content = $("#content");
    content.html("");
    $(articles).each(function (index, article) {
        var test = $("#default-card").clone();
        test.removeAttr("id");
        test.css("display", "inherit");
        test.find(".card-title").text(article.title);
        test.find(".card-subtitle").text(article.author);
        test.find(".card-text").text(article.description);
        test.find(".btn.btn-primary").attr("href", article.url);
        test.find(".card-img-top").attr("src", article.urlToImage);
        if(!article.urlToImage){
            test.find(".card-img-top").remove();
        }
        content.append(test);
    })
}
