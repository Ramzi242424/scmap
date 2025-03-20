<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подать объявление</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/add-listing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ваш_API_ключ&lang=ru_RU" type="text/javascript"></script>
</head>
<body>
    <!-- Шапка сайта -->
    <header class="header">
        <a href="index.php">
            <img src="assets/images/map.png" alt="Логотип" class="logo">
        </a>
        <nav class="user-menu">
            <a href="#"><i class="fas fa-user"></i> Личный кабинет</a>
            <a href="#" class="add-listing-btn"><i class="fas fa-plus"></i> Подать объявление</a>
        </nav>
    </header>

    <!-- Основной контент -->
    <main class="add-listing-container">
        <h1>Подать объявление</h1>
        
        <form id="addListingForm" class="listing-form" method="POST" enctype="multipart/form-data">
            <!-- Основная информация -->
            <section class="form-section">
                <h2>Основная информация</h2>
                <div class="form-group">
                    <label for="title">Заголовок объявления*</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Категория*</label>
                    <select id="category" name="category" required>
                        <option value="">Выберите категорию</option>
                        <option value="apartment">Квартира</option>
                        <option value="house">Частный дом</option>
                        <option value="commercial">Коммерческая недвижимость</option>
                        <option value="land">Земельный участок</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Цена*</label>
                        <input type="number" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="currency">Валюта</label>
                        <select id="currency" name="currency">
                            <option value="TJS">Сомони</option>
                            <option value="USD">Доллары</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- Расположение -->
            <section class="form-section">
                <h2>Расположение</h2>
                <div class="form-group">
                    <label for="city">Город*</label>
                    <select id="city" name="city" required>
                        <option value="">Выберите город</option>
                        <option value="dushanbe">Душанбе</option>
                        <option value="khujand">Худжанд</option>
                        <option value="kulob">Куляб</option>
                        <option value="bokhtar">Бохтар</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="district">Район</label>
                    <select id="district" name="district">
                        <option value="">Сначала выберите город</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="address">Адрес*</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="map-container">
                    <div id="map" style="width: 100%; height: 300px;"></div>
                    <input type="hidden" id="coordinates" name="coordinates">
                </div>
            </section>

            <!-- Фотографии -->
            <section class="form-section">
                <h2>Фотографии</h2>
                <div class="photo-upload-container">
                    <div class="photo-upload-area" id="photoUploadArea">
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="photo-input">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p>Перетащите фото сюда или нажмите для выбора</p>
                        <span class="photo-limit">Максимум 10 фотографий</span>
                    </div>
                    <div class="photo-preview" id="photoPreview"></div>
                </div>
            </section>

            <!-- Описание -->
            <section class="form-section">
                <h2>Описание</h2>
                <div class="form-group">
                    <label for="description">Описание объявления*</label>
                    <textarea id="description" name="description" rows="5" required></textarea>
                    <span class="char-counter">0/1000</span>
                </div>
            </section>

            <!-- Контактная информация -->
            <section class="form-section">
                <h2>Контактная информация</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Телефон*</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="whatsapp">WhatsApp</label>
                        <input type="tel" id="whatsapp" name="whatsapp">
                    </div>
                </div>
            </section>

            <!-- Кнопки действий -->
            <div class="form-actions">
                <button type="submit" class="btn-publish">Опубликовать</button>
                <button type="button" class="btn-draft">Сохранить как черновик</button>
            </div>
        </form>
    </main>

    <script>
        // Инициализация карты
        ymaps.ready(initMap);
        
        function initMap() {
            var myMap = new ymaps.Map("map", {
                center: [38.5598, 68.7870], // Координаты Душанбе
                zoom: 12,
                controls: ['zoomControl', 'searchControl']
            });

            // Создаем метку
            var myPlacemark = new ymaps.Placemark(myMap.getCenter(), {}, {
                draggable: true
            });

            myMap.geoObjects.add(myPlacemark);

            // Обновляем координаты при перетаскивании метки
            myPlacemark.events.add('dragend', function () {
                var coords = myPlacemark.geometry.getCoordinates();
                document.getElementById('coordinates').value = coords.join(',');
            });

            // Клик по карте
            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
                myPlacemark.geometry.setCoordinates(coords);
                document.getElementById('coordinates').value = coords.join(',');
            });
        }

        // Обработка загрузки фотографий
        const photoInput = document.getElementById('photos');
        const photoPreview = document.getElementById('photoPreview');
        const uploadArea = document.getElementById('photoUploadArea');

        photoInput.addEventListener('change', handlePhotos);
        
        function handlePhotos(e) {
            const files = Array.from(e.target.files);
            
            if (files.length > 10) {
                alert('Максимальное количество фотографий - 10');
                return;
            }

            photoPreview.innerHTML = '';
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const preview = document.createElement('div');
                        preview.className = 'preview-item';
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="remove-photo" data-index="${index}">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        photoPreview.appendChild(preview);
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
        }

        // Обработка перетаскивания файлов
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            photoInput.files = files;
            handlePhotos({target: {files: files}});
        }

        // Динамическая загрузка районов
        const citySelect = document.getElementById('city');
        const districtSelect = document.getElementById('district');
        
        const districts = {
            dushanbe: ['Шохмансур', 'Сино', 'Фирдавси', 'И. Сомони'],
            khujand: ['Бободжон Гафуров', 'Спитамен', 'Джаббор Расулов'],
            kulob: ['Центр', 'Зарбдор', 'Восе'],
            bokhtar: ['Центр', 'Левобережный', 'Правобережный']
        };

        citySelect.addEventListener('change', function() {
            const city = this.value;
            districtSelect.innerHTML = '<option value="">Выберите район</option>';
            
            if (districts[city]) {
                districts[city].forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.toLowerCase();
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        });

        // Счетчик символов в описании
        const description = document.getElementById('description');
        const charCounter = document.querySelector('.char-counter');
        
        description.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length}/1000`;
            
            if (length > 1000) {
                this.value = this.value.substring(0, 1000);
                charCounter.textContent = '1000/1000';
            }
        });
    </script>
</body>
</html> 