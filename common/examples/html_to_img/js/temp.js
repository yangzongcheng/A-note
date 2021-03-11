var vm = new Vue({
    el: '#data-main',
    data: {
        info: "",
    },
    //页面载入时候加载
    created:function(){
        this.get_data();
    },
    methods: {
        get_data: function() {
            var obj = this;
            var $url = 'http://'+document.domain+':'+location.port+'/?mod=tool&func=tool&action=teamph&opera=getData';
            $.ajax({
                url:$url,
                dataType:'json',
                success:function(res){
                    if(res.errcode!=200){
                        return alert('数据获取失败');
                    }else{
                        return obj.info = res.data;
                    }
                }
            });

        },//页面载入完成后加载

    }
})