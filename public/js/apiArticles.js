// --------------------------------------------------------------------------------------------------------
// hydratation articles homepage --------------------------------------------------------------------------
const API_ENDPOINT = "https://newsapi.org/v2/top-headlines"
const API_KEY = "a3bc1825ad8d416fafdaf29e5477dc77";

// appel API
function apiCall(country, language, size) {
    $.ajax({
        url: `${API_ENDPOINT}?country=${country}&language=${language}&pageSize=${size}&apiKey=${API_KEY}`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data['articles'])
            displayArticles(data['articles']);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('#0001 - Error:', errorThrown);
            console.error('#0001 - Error:', textStatus);
            console.error('#0001 - Error:', jqXHR);
        }
    });
}

// fonction permettant d'hydrater les .cards vides en HTML (afficher les articles)
function displayArticles(articles) {
    // couleur aléatoire pour le nom de la source de l'article
    var colors = ['yellow', 'blue', 'purple'];
    // conteneur html des articles
    var cards = $('.grid-container .card');
    // on vérifie qu'il y a assez d'articles dans la réponse de l'appel API pour hydrater les cards nécessaire
    if (cards.length < articles.length) {
        console.error("Il n'y a pas assez de cartes dans le HTML pour tous les articles.");
        return;
    }

    // pour chaque card dans le conteneur .grid-container
    cards.each(function(index, card) {
        // si le nombre d'articles récupérés ne suffit pas pour hydrater tous les éléments HTML, erreur
        if (index >= articles.length) return false;

        // on utilise le mot-clé "let" car il a une portée de 'bloc' et non de fonction comme "var" donc c'est plus approprié ici

        // on récupère l'article dans le tableau articles de l'API en utilisant l'index de la card actuelle (boucle cards.each())
        let article = articles[index];

        // si l'article n'a pas d'image, image de remplacement
        let img = article['urlToImage'] ? article['urlToImage'] : '/public/img/missing.jpg';
        // couleur aléatoire dans le tableau de couleur
        let color = colors[Math.floor(Math.random() * colors.length)];
        // on limite le titre à 35 caractères avec une fonction de trim personnalisée pour ne pas couper en plein milieu d'un mot
        let title = article['title'] ? trimString(article['title'], 35) : 'No title found';
        // limite la description aussi
        let description = article['description'] ? trimString(article['description'], 150) : 'No description found';

        // on hydrate les balises vides avec les données récupérées
        $(card).find('img').attr('src', img);
        $(card).find('h4').text(article['source']['name']).addClass(color);
        $(card).find('h3 a').text(title);
        $(card).find('p').text(description);
    });
}

// fonction de trim personnalisée pour limiter un string à une certaine longueur sans couper en plein milieu d'un mot
function trimString(title, maxLength) {
    if (title.length <= maxLength) {
        return title;
    }

    var trimmed = title.substring(0, maxLength);
    var lastSpace = trimmed.lastIndexOf(' ');
    if (lastSpace > 0) {
        trimmed = trimmed.substring(0, lastSpace);
    }

    return trimmed + '...';
}

console.log(apiCall('us', 'en', '7'));
console.log('test');
// --------------------------------------------------------------------------------------------------------