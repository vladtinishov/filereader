<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>
<body>
    <div id="app">
        <div class="inner_app" v-if="!isTableActive">
            <div class="select">
                <div class="mb-3">
                    <label for="formFile" class="form-label">
                        Выберите файл с общим расписанием
                    </label>
                    <input class="form-control" type="file" id="file" @input="gotFile">
                </div>
                <!-- <div class="file" v-if="isSelected">
                    <a
                        href="test_new.zip"
                        class="btn btn-primary"
                    >
                        Получить расписания
                    </a>
                </div> -->
                <div v-if="pages != 0" class="pages">
                    <button
                        @click="getTimetable(page)"
                        v-for="(page, index) in pages"
                        class="btn btn-light page_button"
                        v-if="isNumber(files[page - 1].slice(4)[0]) && files[page - 1].slice(4)[1] != ','"
                    >
                            {{files[page - 1].slice(4)}}
                    </button>
                </div>
            </div>
        </div>
        <div v-if="this.isTableActive" class="tableContainer">
            <div class="buttons">
                <button class="btn btn-primary" @click="closeTable">Закрыть</button>
                <a class="btn btn-info" :href="currentFile">Скачать</a>
            </div>
            <div class="table" v-html="currentPage"></div>
        </div>
    </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
let app = new Vue({
    el: '#app',
    data: {
        isSelected: false,
        isTableActive: false,
        file: '',
        pages: 0,
        currentPage: '',
        button: '<button>button</button>',
        files: [],
        currentFile: 0,
    },
    methods: {
        selected() {
            this.isSelected = true;
            this.selectedSpecialty = document.getElementById('select').value;
        },
        gotFile() {
            this.isSelected = true;
            let file = document.getElementById("file").files[0];
            let formData = new FormData();
            formData.append("file", file);

            axios.post('createArchive.php', formData).then(data => {
                this.file = data.data.file;
                this.pages = data.data.pages;
                this.files = data.data.files;
                if (data.data.files.length === 0) alert('no files');
            });
        },
        getTimetable(page) {
            this.setCurrentPage(this.files[page - 1])
            console.log(page);
            this.isTableActive = true;
            axios.get('getPage.php?page=' + page + '&file=' + this.file)
                .then(data => this.currentPage = data.data)
        },
        closeTable() {
            this.isTableActive = false;
        },
        setCurrentPage(page) {
            this.currentFile = page;
            console.log(this.currentFile);
        },
        isNumber(char) {
            return /\d/.test(char);
        }
    },
})
</script>
<style>
.inner_app {
    width: 100%;
    height: 100%;
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 150px;
    z-index: 1;
}
.file {
    margin-top: 20px;
    text-align: center;
}
.tableContainer {
    width: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    padding: 10px 200px;
}
.table {
    margin-top: 10px;
    border: 1px solid gray;
}
.pages {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.page_button {
    margin-top: 10px;
}
.buttons {
    width: 100%;
    display: flex;
    justify-content: space-between;
}
</style>
</body>
</html>