<template>
  <div class="hello">
    hhhhhhhhhhhh
  </div>
</template>

<script>
import JSZip from "jszip";
import FileSaver from "file-saver";

export default {
  name: 'DownHandle',
  props: {
    msg: String
  },
  downloadData:[],
  down(){
    //下载
    this.downloadData = this.fileData.data;
    console.log(this.downloadData);
    const zip = new JSZip();
    const promises = [];
    this.downloadData.forEach((item) => {
      const promise = this.getFile(item.src).then((data) => {
        const file_name = item.name;
        zip.file(file_name , data, { binary: true });
      });
      promises.unshift(promise);
    });
    Promise.all(promises).then(() => {
      zip.generateAsync({ type: "blob" }).then((content) => {
        console.log(content);
        saveAs(content, "文件包.zip"); // 利用file-saver保存文件  自定义文件名
      });
    });
  },
  getFile(url) {
    return new Promise((resolve, reject) => {
      fetch(url)
          .then((data) => {
            // console.log(data);
            return data;
          })
          .then((res) => {
            resolve(res.blob());
          });
    });
  },
  //导出文件数据包
  fileData:{
    "ts": 1653458074,
    "errno": 0,
    "error": "操作成功",
    "data": [
      {
        "name": "杨旭旭/207871050pdf用例.pdf",
        "src": "https://att0.i-school.net/attachment/get/F000G5oCAEk9LwbKU4tiAAFwZGYgM0F4f_c1ZYVK3JGaHRMLmQ.."
      },
      {
        "name": "杨旭旭/259630263word用例.docx",
        "src": "https://att0.i-school.net/attachment/get/F000HJoCAEk9LwbOU4tiAAFkb2N4AHZVu7PPAo_1thWrfmpT0w.."
      },
      {
        "name": "杨旭旭/93123199自测2022-04-07.xlsx",
        "src": "https://att0.i-school.net/attachment/get/F000HZoCAEk9LwbRU4tiAAF4bHN4vG4jqOBzjWOJQVqXazvlhw.."
      },
      {
        "name": "杨旭旭/356018254infinity-4535699.jpg",
        "src": "https://att0.i-school.net/attachment/get/F000HpoCAEk9LwbaU4tiAAFqcGcgmH3HWXxEsq2wIWJXMBIgVw.."
      },
      {
        "name": "杨旭旭/585936947infinity-4535699.jpg",
        "src": "https://att0.i-school.net/attachment/get/F000H5oCAEk9LwbeU4tiAAFqcGcgDUusJiy5wYbwDehNlqSFXQ.."
      }
    ]
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
