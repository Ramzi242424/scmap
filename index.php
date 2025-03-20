<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доска объявлений</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/add-listing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ваш_API_ключ&lang=ru_RU" type="text/javascript"></script>
</head>
<body>
    <!-- Шапка сайта -->
    <header class="header">
        <img src="assets/images/map.png" alt="Логотип" class="logo">
        <nav class="user-menu">
            <a href="#"><i class="fas fa-user"></i> Личный кабинет</a>
            <a href="#"><i class="fas fa-user-plus"></i> Регистрация</a>
            <a href="#"><i class="fas fa-heart"></i> Избранное</a>
            <a href="add-listing.php" class="add-listing-button"><i class="fas fa-plus"></i> Подать объявление</a>
        </nav>
    </header>

    <!-- Поисковая секция -->
    <section class="search-section">
        <form class="search-form">
            <input type="text" class="search-input" placeholder="Что вы ищете?">
            <select class="location-select">
                <option value="">Выберите город</option>
                <option value="dushanbe">Душанбе</option>
                <option value="khujand">Худжанд</option>
                <option value="kulob">Куляб</option>
                <option value="bokhtar">Бохтар</option>
            </select>
            <button type="submit" class="search-button">Найти</button>
        </form>
    </section>

    <!-- Категории -->
    <section class="categories">
        <a href="#" class="category-item">
            <i class="fas fa-home"></i>
            <span>Недвижимость</span>
        </a>
        <a href="#" class="category-item">
            <i class="fas fa-car"></i>
            <span>Транспорт</span>
        </a>
        <a href="#" class="category-item">
            <i class="fas fa-briefcase"></i>
            <span>Вакансии</span>
        </a>
        <a href="#" class="category-item">
            <i class="fas fa-phone"></i>
            <span>Телефон и связь</span>
        </a>
        <a href="#" class="category-item">
            <i class="fas fa-tools"></i>
            <span>Услуги</span>
        </a>
    </section>

    <!-- Объявления -->
    <section class="listings">
        <h2>Новые объявления</h2>
        <div class="listings-grid">
            <!-- Пример карточки объявления -->
            <div class="listing-card">
                <div class="listing-image-container">
                    <img src="assets/images/kvartira.jpg" alt="Объявление" class="listing-image">
                    <span class="listing-category">Недвижимость</span>
                </div>
                <div class="listing-info">
                    <h3 class="listing-title">3-комнатная квартира, 75 м²</h3>
                    <p class="listing-price">2 500 000 TJS</p>
                    <p class="listing-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Душанбе, Сино
                    </p>
                    <div class="listing-details">
                        <span><i class="fas fa-expand"></i> 75 м²</span>
                        <span><i class="fas fa-building"></i> 5/9 этаж</span>
                    </div>
                </div>
            </div>
            <!-- Конец примера карточки -->
        </div>
    </section>

    <!-- Карта -->
    <section class="map-section">
        <div id="map"></div>
        <div class="map-controls">
            <button onclick="toggleCluster()">Включить/выключить кластеризацию</button>
        </div>
    </section>

    <!-- Подвал -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>О нас</h3>
                <p>Мы помогаем людям находить нужные товары и услуги</p>
            </div>
            <div class="footer-section">
                <h3>Контакты</h3>
                <p>Email: info@example.com</p>
                <p>Телефон: +992 XXX XX XX XX</p>
            </div>
            <div class="footer-section">
                <h3>Следите за нами</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Все права защищены</p>
        </div>
    </footer>

    <script>
        // Инициализация карты
        ymaps.ready(init);
        function init(){
            var myMap = new ymaps.Map("map", {
                center: [38.5598, 68.7870], // Координаты Душанбе
                zoom: 7,
                controls: ['zoomControl', 'searchControl', 'typeSelector'],
                // Ограничиваем область просмотра Таджикистаном
                restrictMapArea: [[36.6711, 67.3872], [41.0421, 75.1372]]
            });

            // Устанавливаем ограничения на зум и стиль карты
            myMap.options.set('minZoom', 6);
            myMap.options.set('maxZoom', 15);

            // Отключаем отображение соседних стран
            myMap.options.set({
                // Запрещаем скролл карты
                scrollZoomDisabled: true,
                // Устанавливаем свой стиль карты
                restrictMapArea: [[36.6711, 67.3872], [41.0421, 75.1372]]
            });

            // Создание собственного макета метки
            var customLayoutClass = ymaps.templateLayoutFactory.createClass(
                '<div class="custom-placemark">' +
                    '<img src="assets/icon/pin.png" alt="Метка" class="placemark-icon">' +
                    '<div class="placemark-price">{{ properties.price }}</div>' +
                '</div>'
            );

            // Создание кластеризатора с собственным макетом
            var clusterer = new ymaps.Clusterer({
                preset: 'islands#invertedVioletClusterIcons',
                clusterIconLayout: customLayoutClass,
                groupByCoordinates: false,
                clusterDisableClickZoom: true,
                clusterHideIconOnBalloonOpen: false,
                geoObjectHideIconOnBalloonOpen: false
            });

            // Примеры объявлений с координатами
            var listings = [
                {
                    coordinates: [38.5598, 68.7870],
                    title: '3-комнатная квартира',
                    price: '2 500 000 TJS',
                    address: 'Душанбе, Сино',
                    category: 'Недвижимость',
                    image: 'D:/MyDear/test1/assets/images/apartment.jpg'
                },
                {
                    coordinates: [39.9087, 69.0167],
                    title: '2-комнатная квартира',
                    price: '1 800 000 TJS',
                    address: 'Худжанд, Центр',
                    category: 'Недвижимость',
                    image: 'D:/MyDear/test1/assets/images/apartment.jpg'
                }
            ];

            // Создание меток для объявлений с новым макетом
            var placemarks = listings.map(function(listing) {
                return new ymaps.Placemark(listing.coordinates, {
                    balloonContentHeader: listing.title,
                    balloonContentBody: `
                        <div class="map-balloon">
                            <img src="${listing.image}" alt="${listing.title}" style="width:200px;height:150px;object-fit:cover;margin-bottom:10px;">
                            <p style="font-weight:bold;color:#0066FF;font-size:16px;">${listing.price}</p>
                            <p>${listing.address}</p>
                            <p>${listing.category}</p>
                        </div>
                    `,
                    price: listing.price
                }, {
                    iconLayout: customLayoutClass,
                    iconShape: {
                        type: 'Rectangle',
                        coordinates: [[0, 0], [40, 40]]
                    }
                });
            });

            // Добавление меток в кластеризатор
            clusterer.add(placemarks);
            myMap.geoObjects.add(clusterer);
        }

        // Функция переключения кластеризации
        function toggleCluster() {
            // Реализация переключения кластеризации
        }
    </script>

    <!-- Добавляем информацию об авторстве иконок -->
    <div class="icon-credits">
        Автор иконок: <a href="https://www.flaticon.com/ru/authors/smashingstocks" title="smashingstocks">smashingstocks</a> from <a href="https://www.flaticon.com/ru/" title="Flaticon">www.flaticon.com</a>
    </div>
</body>
</html> 