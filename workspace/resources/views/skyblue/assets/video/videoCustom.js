//<![CDATA[
var recordGridColumn = 0;
var nonSorting = [1, 2];
/* Initialize object for player*/
var myPlaylist;
/* Get the folder path where the media files uploaded*/
var PathMediaFiles = siteUrl + "videogallery/";
/**
 * This functions add the media files into the player.
 * @param string file
 * @returns {undefined}
 */
function addAudioVideo(file) {
    var extension = file.substr((file.lastIndexOf('.') + 1));
    extension = extension.toLowerCase();
    console.log(extension);
    switch (extension) {
        case 'mp4':
            play_file = {
                title: file,
                artist: "Unknown",
                m4v: PathMediaFiles + file
            };
            break;
        case 'flv':
            play_file = {
                title: file,
                artist: "Unknown",
                m4v: PathMediaFiles + file
            };
            break;
        case 'mp3':
            play_file = {
                title: file,
                artist: "Unknown",
                mp3: PathMediaFiles + file
                        /*poster: "http://www.jplayer.org/audio/poster/Miaow_640x360.png"*/
            };
            break;
        default:
            console.log(extension);
            console.log('who knows');
            break;
    }
    myPlaylist.add(play_file);
}
/**
 * Create jplayer with config variables.
 * @param {type} param
 */
$(document).ready(function () {
    myPlaylist = new jPlayerPlaylist({
        jPlayer: "#jquery_jplayer_N",
        cssSelectorAncestor: "#jp_container_N"
    }, [/*add playlist here {},*/
    ], {
        playlistOptions: {
            enableRemoveControls: false
        },
        swfPath: themePath + "/assets/video/dist/jplayer/jquery.jplayer.swf",
        supplied: "M4A,M4V, oga, mp3,flv,m3ua, m3uv",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        keyEnabled: true,
        audioFullScreen: true,
        autoPlay: true,
        solution: "flash, html",
        remainingDuration: true,
        loop: true

    });
    /**
     * Play all videos listed on the page
     * @returns {undefined}
     */
    function playAllAudioVideo() {
        $('.video_file').each(function (index, item) {
            var video = $(item).val();
            addAudioVideo(video);
        });
        myPlaylist.select(0);
        myPlaylist.play();
    }
    /*myPlaylist.playlist.length;*/
    /* Play all video button event*/
    $("#play_video").click(function () {
        myPlaylist.remove();
        setTimeout(function () {
            playAllAudioVideo();
        }, 1500);
    });
    /* Play individual media file*/
    $(".play_file").click(function () {
        var avFile = $(this).parent().prev().find('.video_file').val();
        myPlaylist.remove();
        setTimeout(function () {
            addAudioVideo(avFile);
            myPlaylist.select(0);
            console.log('play');
            myPlaylist.play();
        }, 1000);
    });
    /* Add media file in playlist*/
    $(".add_file").click(function () {
        var avFile = $(this).parent().prev().prev().find('.video_file').val();
        addAudioVideo(avFile);
    });
});
//]]>
