  $(function () {

        //edit_quiz.html - when saving general quiz data (title, due date)
        $(".save-btn").click(function(e) {
                e.preventDefault();

                $.ajax({
                type: 'post',
                url: '/update_quiz',
                data: $('.saveForm').serialize(),
                success: function (res) {
                   
                    switch (res) {
                        case "0":
                          M.toast({html: 'Quiz not found!', classes: 'failure-toast'}) 
                            break;
                    
                        default:
                           M.toast({html: 'Quiz Saved!', classes: 'success-toast'})
                           //update on page
                            var title = document.getElementById("quiz_title");
                            var displayTitle = document.getElementById("display_title");
                            display_title.innerHTML = title.value;
                            
                            var datePicker = document.getElementById("dateP");
                            var displayDate = document.getElementById("display_date");
                            displayDate.innerHTML= datePicker.value;

                            var timePicker = document.getElementById("timeP");
                            var displayTime = document.getElementById('display_time');
                            displayTime.innerHTML = timePicker.value;

                            break;
                    }
                    
                }
                }).fail(function() {
                     M.toast({html: 'Internal Error!', classes: 'failure-toast'}) 
                });
            });


  });
  