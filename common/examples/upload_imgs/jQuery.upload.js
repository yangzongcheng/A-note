(function($) {
    $.fn.upload = function(look_call_back){

        var  _this = $(this),
        item_html = "<li class='item'></li>",
        $add = $('<li class="item add"><svg class="icon" viewBox="0 0 1024 1024" version="1" xmlns="http://www.w3.org/2000/svg" width="200" height="200"><defs><style/></defs><path d="M737 436a174 174 0 0 1 172 172 172 172 0 0 1-172 172c-69 1-69 107 0 106 152-2 276-125 278-278S886 332 737 330c-69-1-69 105 0 106zM285 779a174 174 0 0 1-172-172 172 172 0 0 1 172-172c68-1 69-106 0-106A282 282 0 0 0 7 607a281 281 0 0 0 278 278c69 1 68-105 0-106z" fill="#4A5699"/><path d="M340 384a174 174 0 0 1 172-172 172 172 0 0 1 172 172c1 68 106 68 106 0a282 282 0 0 0-278-278 281 281 0 0 0-278 278c-1 68 105 68 106 0z" fill="#C45FA0"/><path d="M545 473c17 17 17 43 0 60L422 656a42 42 0 0 1-60-60l123-123c17-16 43-16 60 0z" fill="#F39A2B"/><path d="M485 473c17-16 44-16 60 0l123 123a42 42 0 0 1-60 60L485 533a42 42 0 0 1 0-60z" fill="#F39A2B"/><path d="M514 634c24 0 43 20 43 43v179a43 43 0 0 1-86 0V677c0-23 19-43 43-43z" fill="#E5594F"/></svg></li>'),
        loading = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="300" height="300" class="progress"><g fill="rgba(17,89,164,0.1)"><path d="M 0 70 Q 75 39, 150 70 T 300 70 T 450 70 T 600 70 T 750 70 V 320 H 0 V 0"></path><animateTransform attributeName="transform" attributeType="XML" type="translate" from="0" to="-300" dur="1.5s" repeatCount="indefinite"></animateTransform></g><g fill="rgba(17,89,164,0.15)"><path d="M 0 70 Q 87.5 47, 175 70 T 350 70 T 525 70 T 700 70 T 875 70 T 1050 70 V 320 H 0 V 0"></path><animateTransform attributeName="transform" attributeType="XML" type="translate" from="0" to="-350" dur="3s" repeatCount="indefinite"></animateTransform></g></svg><div class="progressnum"></div>',
        del = '<svg xmlns="http://www.w3.org/2000/svg" class="delete" version="1" viewBox="0 0 1024 1024"><path fill="#fff" d="M512 70a439 439 0 0 1 442 442 439 439 0 0 1-442 442A439 439 0 0 1 70 512 439 439 0 0 1 512 70m0-40a482 482 0 1 0 0 964 482 482 0 0 0 0-964zm114 253v-1c0-21-17-38-38-38H436c-21 0-38 17-38 38v1H282v74h460v-74H626zM321 396v346c0 21 17 38 38 38h306c21 0 38-17 38-38V396H321zm114 306h-76V474h76v228zm115 0h-76V474h76v228zm115 0h-76V474h76v228z"/></svg>',
        look = '<svg xmlns="http://www.w3.org/2000/svg" class="look" version="1" viewBox="0 0 1024 1024"><path fill="#fff" d="M451 835a386 386 0 1 1 0-771 386 386 0 0 1 0 771zm0-675a291 291 0 1 0 0 581 291 291 0 0 0 0-581zm450 798c-15 0-30-5-42-17L658 740a58 58 0 0 1 83-82l201 201a58 58 0 0 1-41 99"/></svg>',
        $input = $('<input type="file" name="file" />'),
        $upload = $('<input type="hidden" />'),
        _moveDom,_x,_y,inX,inY,item_w,item_h,
        config = {
            height:_this.attr("data-height") ? _this.attr("data-height") : 0,
            width:_this.attr("data-width") ? _this.attr("data-width") : 1920,
            type : _this.attr('data-type') ? _this.attr('data-type') : 'png,jpg,jpeg,gif',
            upname : _this.attr("data-file") ? _this.attr("data-file") : 'file',
            inputname : _this.attr("data-name") ? _this.attr("data-name") : 'upload',
            num : _this.attr('data-num') ? _this.attr('data-num') : 10,
            action : _this.attr('action') ? _this.attr('action') : "/index.php",
            size : _this.attr('data-size') ? _this.attr('data-size') : 20480,
            value : _this.attr("data-value") ? _this.attr("data-value") : ""
        };

        _this.append($add.attr("data-num",config.num).attr("data-type",config.type))
            .append($input.attr("multiple",config.num > 1 ? "multiple" : false))
            .append($upload.attr("name",config.inputname))
            .addClass(config.num > 1 ? "multiple" : "one")
            .on("click","li.add",function(){
                $input.click();
            })
            .on("change","input[type='file']",function(e){
                var files = e.target.files;
                var temp = [];
                for(var item in files)
                {
                    if(typeof files[item] == 'object')
                    {
                        temp.push(files[item]);
                    }
                }
                uploadfile(temp);
            })
            .on("click","li.error",function(e){
                delfile($(e.currentTarget));
            })
            .on("click",'svg.delete',function(e)
            { 
                delfile($(e.currentTarget).parent("li.success"));
            })
            .on("click",'svg.look',function(e)
            { 
                if(look_call_back && typeof look_call_back == 'function')
                {
                    look_call_back(_this,$(e.currentTarget).parent("li.success").data("url"));
                }
            })
            .on("mousedown","li.success",function(e){
                var ethis = $(e.target);
                _moveDom = $(this);
                var offset = _moveDom.offset()
                var thisOffset = _this.offset() 
                var left = offset.left;
                var top = offset.top;  
                _x=parseInt(left) + (e.pageX - parseInt(left)) + (thisOffset.left - offset.left);
                _y=parseInt(top) + (e.pageY - parseInt(top)) + (thisOffset.top - offset.top);
                item_w = _moveDom.width() + 2 + _moveDom.css("margin-right").replace("px",'') * 1;
                item_h = _moveDom.height() + 2 + _moveDom.css("margin-bottom").replace("px",'') * 1;
                inX = false;
                inY = false;
            });

        if(config.value) //初始化
        {
            var value = config.value.split(","),i = 0,html = '';
            var count = 0;
            for (var item in value) {
                if(i < config.num && value[i])
                {
                    html = $("<li class='item success'></li>")
                            .append(del)
                            .append(typeof look_call_back == 'function' ? look : "")
                            .data('url',value[i])
                            .attr("data-filename",hash(value[i]))
                            .css("background-image","url('"+value[i]+"')")
                            .insertBefore($add);
                    _this.data('num',++count).removeClass("empty");
                    setfilelist();
                }
                i++;
            }
        }else
        {
            _this.data('num',0).addClass("empty");
        }
        
        $(document).on("mousemove",function(e){
            if(_moveDom){
                var x=e.pageX-_x;
                var y=e.pageY-_y;
                var thisI = _moveDom.index()
                var offset = _this.offset() 
                var qh = _this.height() ? _this.height() : 134
                _moveDom.css({top:y,left:x,zIndex:999,position:'absolute'}); 
                inX = e.pageX - offset.left
                inY = e.pageY - offset.top  
                if(inY > (qh + 20)  || inY < 0)
                {
                    _moveDom.addClass('delete')
                }else
                {
                    _moveDom.removeClass('delete')
                }
                inX = parseInt(inX / item_w)
                inY = parseInt(inY / item_h)
                inX = parseInt(inX + (_this.width() / item_w) * inY)
                if(inX >= thisI) inX++ 
                _this.children("li").not(_moveDom).css("margin-left",0)
                _this.children("li").eq(inX).not(_moveDom).css("margin-left",item_w)

            }
        }).on("mouseup",function(){
            if(_moveDom){
                if(_moveDom.hasClass("delete") && _moveDom.data('filename'))
                { 
                     
                    delfile(_moveDom); 
                    _moveDom=false;
                    _this.children("li").not(".add").css({"position":"relative","top":0,"left":0,'margin-left':0,'zIndex':0})
                    _this.children("li.add").css("margin-left",0)
                    return false;
                }
                var newImg = _moveDom.clone()
                newImg.data('url',_moveDom.data("url"))
                if(inX !== false)
                {
                    if(inX >=_this.children("li").size())
                        newImg.insertBefore($add); 
                    else
                        newImg.insertBefore(_this.children("li").eq(inX)); 
                    _moveDom.remove();
                    setfilelist();
                     _this.children("li").not(".add").css({"position":"relative","top":0,"left":0,'margin-left':0,'zIndex':0})
                    _this.children("li.add").css("margin-left",0)
                }
               
                
            }
            _moveDom=false;
        }).on("dragenter",function(event){
            event.preventDefault(); 
        }).on("dragover",function(event){
            event.preventDefault();
            if($(event.target).hasClass("upload"))
                _this.addClass("dragenter")
            else
                _this.removeClass("dragenter")
        }).on("drop",function(event){
            event.preventDefault();
            _this.removeClass("dragenter");

            var files = event.originalEvent.dataTransfer.files;
            var temp = [];
            for(var item in files)
            {
                if(typeof files[item] == 'object')
                {
                    temp.push(files[item]);
                }
            }
            uploadfile(temp);
            return false
        });

        function uploadfile(e)
        {

            var fileObj = e;
            for (var item in fileObj) {
                if(typeof fileObj[item] == 'object')
                { 
                    var rs = addfile(fileObj[item],function(data,item,file){

                        item.insertBefore($add);
                        var formData = new FormData();
                        formData.append(config.upname,data,file.name); 
                        _this.removeClass("empty");
                        $.ajax({
                            url : config.action,
                            type : "POST",
                            data : formData,
                            dataType:"text",
                            processData : false,
                            contentType : false,
                            success:function(data){

                                console.log(data);
                                data = JSON.parse(data);
                                item.children("svg.progress").remove();
                                item.children(".progressnum").remove();
                                if(data.code)
                                {
                                    item.addClass("success upload-li-images")
                                        .append(del)
                                        .append(typeof look_call_back == 'function' ? look : "")
                                        .data('url',data.msg).attr('src',data.src);
                                }else
                                {
                                    item.addClass("error").attr("data-error",data.msg?data.msg:"服务端返回数据异常");
                                }
                                setfilelist();
                            },
                            xhr:function(){
                                var xhr = new XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt){
                                    if (evt.lengthComputable) {
                                        var percentComplete = Math.round(evt.loaded * 100 / evt.total); 
                                        item.children("svg.progress").css("height",50 + (120 * percentComplete / 100)+"%");
                                        item.children(".progressnum").text(percentComplete) 
                                    }
                                }, false);
                                return xhr;
                            },
                            error:function(){
                                item.addClass("error").attr("data-error","网络连接异常！");
                                setfilelist();
                            }
                        });
                    });
                }
            }
        }

        function addfile(file,callback)
        { 
            //验证文件
            $input.val("");
            if(config.num == 1)
            { 
                _this.children(".item").not(".add").remove();
            }else
            {
                if(config.num <= _this.data('num')) return false;
                _this.data('num',_this.data('num') + 1)
            }

            var size = file.size,
                exp = file.name.toLowerCase().split('.').splice(-1).join(),
                html = $(item_html);
            //验证文件类型
            var yexp = config.type.split(",");
            if(yexp.indexOf(exp) < 0)
            {
                var errorstr = "不能上传."+exp+"的文件!";
            }
            if(["png","jpg","jpeg","gif","bmp"].indexOf(exp) < 0 && size > config.size * 1024)
            {
                var errorstr = "不能上传大于."+config.size+"KB 的文件!";
            } 
            if(errorstr)
            {
                html.append(getfileicon(exp))
                    .addClass("error")
                    .append("<div class='filename'>"+file.name+"</div>")
                    .attr("data-error",errorstr)
                    .insertBefore($add);
                _this.removeClass("empty");
                setfilelist();
                return false;
            }

            var r = new FileReader();
            r.readAsDataURL(file);
            r.onload = function()
            { 
                var thishash = hash(file.name) + hash(file.type) + hash(file.size.toString()) + hash(r.result);
                if(_this.children('li[data-filename="'+thishash+'"]').size() > 0)
                {
                    return false;
                } 
                
                
                html.attr("data-filename",thishash);


                if(["png","jpg","jpeg","gif","bmp"].indexOf(exp) >= 0)
                { 
                    var image = new Image();
                    image.src = r.result;
                    image.onload = function(){
                        var that = this;
                        // 默认按比例压缩
                        var w = that.width,
                        h = that.height,
                        scale = w / h;
                        if(config.width && config.width < w)
                        {
                            w = config.width;
                            h = w / scale;
                        }else if(config.height && h > config.height)
                        {
                            h = config.height;
                            w = h * scale;   
                        }
                        var quality = 1;  // 默认图片质量为0.7
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d'); 
                        var anw = document.createAttribute("width");
                        anw.nodeValue = w;
                        var anh = document.createAttribute("height");
                        anh.nodeValue = h;
                        canvas.setAttributeNode(anw);
                        canvas.setAttributeNode(anh); 
                        ctx.drawImage(that, 0, 0, w, h);
                        // quality值越小，所绘制出的图像越模糊
                        var base64 = canvas.toDataURL('image/png', quality );
                        html.css("background-image","url('"+base64+"')").append(loading);

                        //开始上传
                        var uploadFile = convertBase64UrlToBlob(base64);
                        callback(uploadFile,html,file);
                        return;
                    }; 
                }else
                {
                    var icon = getfileicon(exp);
                    html.append(icon).append("<div class='filename'>"+file.name+"</div>").append(loading);
                    var uploadFile = file;
                    callback(uploadFile,html,file);
                    return;

                }
            }
            
        }
        function convertBase64UrlToBlob(urlData){
            var arr = urlData.split(','), 
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {type:mime});
        }
        function hash(input){
            var hash = 5381,
                I64BIT_TABLE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-'.split(""),
                i = input.length - 1;
            if(typeof input == 'string'){
                for (; i > -1; i--) hash += (hash << 5) + input.charCodeAt(i);
            }
            else{
                for (; i > -1; i--) hash += (hash << 5) + input[i];
            }
            var value = hash & 0x7FFFFFFF; 
            var retValue = '';
            do{
                retValue += I64BIT_TABLE[value & 0x3F];
            }
            while(value >>= 6);
            return retValue;
        }

        function getfileicon(type)
        {
            if(["asp","php","js","java","html","css",'sql'].indexOf(type) >= 0) return  '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><defs/><path fill="#FF8976" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#FFD0C8" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#FFF" d="M422 564l-118 46 118 47v37l-163-65v-37l163-65v37zm116-106h37l-89 240h-37l89-240zm64 200l118-47-118-46v-37l163 64v38l-163 64v-36z"/></svg>';
            if(['psb','psd'].indexOf(type) >= 0) return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#8095FF" d="M168 32c-12 0-25 5-34 14s-14 22-14 34v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L648 32H168z"/><path fill="#CCD5FF" d="M920 304H696c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#C0CAFF" d="M504 550c5-2 10-4 16-4s11 2 16 4l185 108c5 2 8 8 8 13s-3 11-8 14L534 793c-4 2-10 4-16 4s-11-2-16-4L318 686c-5-3-8-8-8-14s3-11 8-14l186-108z"/><path fill="#FFF" d="M504 390c5-2 10-4 16-4s11 2 16 4l185 108c5 2 8 8 8 13s-3 11-8 14L534 633c-4 2-10 4-16 4s-11-2-16-4L318 526c-5-3-8-8-8-14s3-11 8-14l186-108z"/></svg>';
            if(['xls','xlsx','number','et','ett'].indexOf(type) >= 0) return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#5ACC9B" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#BDEBD7" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#FFF" d="M475 538L366 386h76l71 108 74-108h73L549 538l117 161h-76l-79-115-78 116h-75l117-162z"/></svg>';
            if(['wps','wpt','page','doc','docx'].indexOf(type) >= 0) return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#6CCBFF" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#C4EAFF" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#FFF" d="M280 386h65l65 244 72-244h62l72 244 66-244h62l-96 314h-65l-71-242h-1l-72 241h-65l-94-313z"/></svg>';
            if(['ppt','pptx','key','dps','dpt','wpp'].indexOf(type) >= 0) return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#FF8976" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#FFD0C8" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#FFF" d="M386 386h176c70 0 92 47 92 97 0 48-28 97-92 97H446v120h-60V386zm60 145h96c35 0 53-10 53-47 0-38-25-48-48-48H446v95z"/></svg>';
            if(type == 'pdf') return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#FF5562" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#FFBBC0" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#FFF" d="M696 843c-50 0-95-86-119-142-40-17-84-32-127-43-37 25-100 62-149 62-31 0-52-15-60-42-7-21-1-36 5-44 13-18 40-27 80-27 32 0 72 6 118 17 30-21 59-45 86-70-12-56-25-147 8-188 16-20 40-27 70-18 33 10 45 30 49 45 13 54-49 128-91 171 9 38 21 77 36 113 61 27 133 67 141 111 3 15-1 30-13 42-11 8-22 13-34 13zm-74-121c30 61 59 90 74 90 2 0 6-1 10-5 6-5 6-9 5-13-3-16-29-42-89-72zm-296-83c-40 0-51 10-54 14-1 1-4 5-1 17 3 9 9 19 30 19 25 0 62-15 105-40-31-7-58-10-80-10zm158-5c26 8 52 16 77 26-9-23-16-47-23-70l-54 44zm99-258c-9 0-15 3-21 10-16 20-18 73-5 140 49-52 75-100 69-125-1-4-4-15-27-22l-16-3z"/></svg>';
            if(type == 'txt') return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#E5E5E5" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#CCC" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#FFF" d="M264 376h208c14 0 24-10 24-24s-10-24-24-24H264c-14 0-24 10-24 24s10 24 24 24zm0 160h496c14 0 24-10 24-24s-10-24-24-24H264c-14 0-24 10-24 24s10 24 24 24zm496 112H264c-14 0-24 10-24 24s10 24 24 24h496c14 0 24-10 24-24s-10-24-24-24z"/></svg>';
            if(['zip','rar','gzip','7-zip','zipz','rarr','iso'].indexOf(type) >= 0) return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#5ACC9B" d="M944 944H80c-26 0-48-18-48-41V656h960v247c0 23-22 41-48 41z"/><path fill="#6CCBFF" d="M80 80h864c26 0 48 18 48 41v247H32V121c0-23 22-41 48-41z"/><path fill="#FFD766" d="M32 368h960v288H32z"/><path fill="#FF5562" d="M352 80h320v864H352z"/><path fill="#FFF" d="M444 128h64v48h-64zm64-48h64v48h-64zm0 96h64v48h-64zm-64 48h64v48h-64zm64 48h64v48h-64zm-64 48h64v48h-64zm64 48h64v48h-64zm-64 48h64v48h-64zm64 48h64v48h-64zm-64 48h64v48h-64zm64 48h64v48h-64zm-64 48h64v48h-64zm64 48h64v48h-64zm-64 48h64v48h-64zm64 48h64v48h-64zm-64 48h64v48h-64zm0 96h64v48h-64zm64-48h64v48h-64z"/></svg>';
            if(['avi','wmv','mpeg','mp4','mov','mkv','flv','f4v','m4v','rmvb','rm','3gp','dat','ts','mts','vob'].indexOf(type) >= 0) return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#8095FF" d="M80 34h864v960H80z"/><path fill="#FFF" d="M136 112a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM136 272a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM136 432a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM136 592a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM136 752a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM136 912a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM824 112a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM824 272a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM824 432a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM824 592a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM824 752a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM824 912a40 40 0 1 0 80 0 40 40 0 1 0-80 0zM648 508L436 362c-5-3-11-4-17 0-5 3-9 8-9 14v290c0 6 4 12 9 15 6 3 12 2 17-1l212-146c5-3 7-8 7-13s-3-10-7-13z"/></svg>';
            if(['gif','jpg','jpeg','png','bmp'].indexOf(type) >= 0)return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><defs/><path fill="#FF5562" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#FFBBC0" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/><path fill="#FFF" d="M758 706L658 550c-3-4-8-7-13-7s-11 3-14 7l-53 84-120-195c-4-5-8-7-14-7s-10 3-14 7L266 706c-4 4-4 11 0 16 3 5 8 8 13 8h466c5 0 11-4 14-8 3-6 3-12-1-16zM622 412a40 40 0 1 0 80 0 40 40 0 1 0-80 0z"/></svg>';
            return '<svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path fill="#E5E5E5" d="M160 32a49 49 0 0 0-48 48v864c0 12 5 25 14 34 10 9 22 14 34 14h704c12 0 25-5 34-14 9-10 14-22 14-34V304L640 32H160z"/><path fill="#CCC" d="M912 304H688c-12 0-25-5-34-14s-14-22-14-34V32l272 272z"/></svg>';
        }

        function setfilelist()
        {
            var filelist = [];
            _this.children('li.success').each(function(){
                filelist.push($(this).data("url"));
            }); 
            if(_this.children('li').not(".add").size() >= config.num &&  config.num > 1) $add.hide();
            else $add.show();
            if(_this.children('li').not(".add").size() == 0) _this.addClass('empty')
            $upload.val(filelist.join(","));
             
        }

        function delfile(item)
        {
            item.fadeOut(333,function(){item.remove();
            setfilelist()});
            _this.data('num',_this.data('num') - 1);

        }

    
    }
})(jQuery);