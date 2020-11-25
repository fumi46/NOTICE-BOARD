$(function(){

    /* ドロップダウンメニュー */
    /* 初期表示 */
    if(window.matchMedia('(max-width: 600px)').matches){
        $('.drop_hidden').hide();  //スマホ対応
    }else{
        $('.drop_hidden').show();  //PC対応
    }

    /* ドロップダウンメニュークリック時 */
    $('#navi li').click(function(){
        if($('.drop_hidden').is(':hidden')){     //ドロップダウンメニューが表示されていなければ表示させる
            $('.drop_hidden:not(:animated)').slideDown('fast');  
        }else{
            $('.drop_hidden:not(:animated)').slideUp('fast');
        }
    });

    /* ウィンドウがリサイズされた時 */
    $(window).resize(function(){
        if(window.matchMedia('(max-width: 600px)').matches){
            $('.drop_hidden').hide();  //スマホ対応
        }else{
            $('.drop_hidden').show();  //PC対応
        }
    });

    /* 入力文字数カウント(キーアップ) */
    $('textarea').keyup(function(){
        var str = $(this).val();
        var count = str.length;
        $('#count').html(count);
    });

});