
function onload() {
    var line = localStorage.getItem('slinedata') == null ? Morris.Line(inichart('linedata')) : localStorage.getItem('slinedata');
    // sessionStorage.getItem('slinedata') == null ? Morris.Line(inichart('linedata')) : sessionStorage.getItem('slinedata');
    //sessionStorage.setItem('slinedata', line);
    localStorage.setItem('slinedata', line)
    var data = localStorage.getItem('selectsensor');
    //sessionStorage.getItem('selectsensor');
    alert(data);
    if (data != null) {
        var getsensor = data; //sessionStorage.getItem('selectsensor');
        histdata(getsensor);
    }
}

setInterval(function () {
    $("#mainframework").load(location.href + " #mainframework>*", "");
}, 10000) //1min
function updateData(arr) //jsontype
{
    return arr;
}

function inichart(_element) {
    option = {
        element: _element,
        data: updateData(),
        xkey: 'record_time',
        ykeys: ["data"],
        labels: ["Total Outcome"],
        fillOpacity: 0.6,
        hideHover: "auto",
        behaveLikeLine: true,
        resize: true,
        pointFillColors: ["#ffffff"],
        pointStrokeColors: ["black"],
        lineColors: ["#87AAAA"]
    };
    return option;
}

function renameKey(obj, oldKey, newKey) {
    obj[newKey] = obj[oldKey];
    delete obj[oldKey];
}

function timestamp2now(obj) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key)) {
            var unix_time = obj[key].record_time;
            var a = new Date(unix_time * 1000);
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var year = a.getFullYear();
            var month = months[a.getMonth()];
            var date = a.getDate();
            var hour = a.getHours();
            var min = a.getMinutes();
            var sec = a.getSeconds();
            var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
            obj[key]['record_time'] = time;
            console.log(obj[key]['record_time']);
        }
    }
    return JSON.stringify(obj);

}
//var fill = Morris.Area(inichart('fill'));
// https://blog.reh.tw/archives/662
//https://blog.csdn.net/mafan121/article/details/50832873
function histdata(sensor) {
    var xmlhttp;
    //var line = Morris.Line(inichart('linedata'));
    localStorage.setItem('selectsensor', sensor);
    //sessionStorage.setItem('selectsensor', sensor);
    //Session['selectsensor']=sensor;
    xmlhttp = new XMLHttpRequest();
    var sen = '';
    switch (sensor) {
        case 'co2':
            sen = 'co2';
            break;
        case 'temp':
            sen = 'temp';
            break;
        case 'humid':
            sen = 'humid';
            break;
        case 'lux':
            sen = 'lux';
            break;
        case 'soil_humid':
            sen = 'soil';
            break;
        case defualt:
            alert('all');
            break;
    }

    $.ajax({
        type: 'POST',
        url: "getghdata.php",
        dataType: "json",
        data: {
            sensor: sen,
            gh: '<?php print($nowgh); ?>'
        },
        success: function (data) {
            var arr = JSON.parse(data);
            switch (sensor) {
                case 'co2':
                    arr.forEach(obj => renameKey(obj, 'co2', 'data')); //obj,origin name ,update name
                    break;
                case 'temp':
                    arr.forEach(obj => renameKey(obj, 'temperature', 'data'));
                    break;
                case 'humid':
                    arr.forEach(obj => renameKey(obj, 'humidity', 'data'));
                    break;
                case 'lux':
                    arr.forEach(obj => renameKey(obj, 'accumulation_lux', 'data'));
                    break;
                case 'soil_humid':
                    sen = 'soil';
                    break;
                case defualt:
                    alert('all');
                    break;
            }
            line = localStorage.getItem('slinedata') == null ? Morris.Line(inichart('linedata')) : localStorage.getItem('slinedata');
            var dd = timestamp2now(arr);
            console.log(dd);
            line.setData(updateData(JSON.parse(dd)));
        },
        error: function (jqXHR) {
            alert('發生錯誤：' + jqXHR.status);
        }

    });
}
