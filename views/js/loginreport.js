$(function() {
    $('#lst_date_range').daterangepicker({
        ranges:{
          'Today'         : [moment(),moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')]
        }
    }) 

    $('#lst_date_range').on("change", function(){  
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        
        var data = new FormData();
        data.append("start_date", start_date);
        data.append("end_date", end_date);

        $.ajax({
            url:"ajax/login_report.ajax.php",   
            method: "POST",                
            data: data,                    
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
                $('.login_content').empty(); 
                var html = [];

                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                    html.push('<table class="table mx-auto w-auto">');
                        html.push('<thead>');
                            html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Time</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Day</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Employee</th>');
                            html.push('</tr>');
                        html.push('</thead>');

                        for(var i = 0; i < answer.length; i++) {
                            var login = answer[i];
                            var cdate = login.cdate;

                            var cur_date = login.cdate;
                            var cdate = cur_date.substring(5, 7) + '/' + cur_date.substring(8, 10) + '/' + cur_date.substring(0, 4);

                            var ctime = login.ctime;
                            var cday = login.cday;
                            var full_name = login.full_name;
                            html.push('<tr>');
                                html.push('<td>'+cdate+'</td>');
                                html.push('<td style="text-align:left;">'+ctime+'</td>');
                                html.push('<td style="text-align:left;">'+cday+'</td>');
                                html.push('<td style="text-align:left;">'+full_name+'</td>');
                            html.push('</tr>');
                        } 

                    html.push('</table>');
                html.push('</div>');
                $('.login_content').html(html.join(''));
            }
       })    
    }); 
});