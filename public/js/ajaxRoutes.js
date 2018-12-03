                var title = document.getElementById("quiz_title").value;

               
  $(function () {

        //edit_quiz.html - when saving general quiz data (title, due date)
        $(".save-btn").click(function(e) {
                e.preventDefault();
                if (document.getElementById("quiz_title").value === "") {
                    e.preventDefault();

                    M.toast({html: 'Title field cannot be empty!', classes: 'failure-toast'}) 
                    document.getElementById("quiz_title").value = title;
                    return false;
                } else {
                    postQuiz()
                }

                    function postQuiz() {
                        $.ajax({
                            type: 'post',
                            url: '/professor-dashboard/quiz/update_quiz',
                            data: $('.saveForm').serialize(),
                            success: function (res) {
                                res = JSON.parse(res);
                                switch (res.status) {
                                    case "0":
                                    M.toast({html: 'Quiz not found!', classes: 'failure-toast'}) 
                                    console.log(res.error)
                                        break;
                                
                                    default:
                                    console.log(res.error)
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
                }
                
            });


  });
  