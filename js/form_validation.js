
            //<![CDATA[
            $j(function() {

                if (false) {
                    bpo.exceedsPlanCap("OWNER", true,
                        true);
                    $j('a.js-over-plan-cap').click(function () {
                        bpo.exceedsPlanCap("OWNER", true,
                            true);
                    });
                }

              // Toggle Optional Settings
                $j('.optional-toggle').click(function(){
                if ($j('.optional-container').hasClass('expanded')) {
                  $j('.optional').slideUp(100);
                  $j('.optional-toggle span').text("Show Optional Settings");
                  $j('.optional-container').removeClass('expanded');
                }
                else {
                  $j('.optional').slideDown(100);
                  $j('.optional-toggle span').text("Hide Optional Settings");
                  $j('.optional-container').addClass('expanded');
                }
              });

              $j("label").click(function(){
                  if ($j(this).attr("for") != "")
                      $j("#" + $j(this).attr("for")).attr('checked', 'checked');
              });

              // Set Active label on page load
              $j('label').each(function() {
                  if ($j(this).find('input').is(':checked')){
                      $j(this).parents('ul').find('li').removeClass('active');
                      $j(this).parents('li').addClass('active');
                  }
              });
              $j('label').click(function() {
                  if ($j(this).find('input').is(':checked')){
                      $j(this).parents('ul').find('li').removeClass('active');
                      $j(this).parents('li').addClass('active');
                  }
              });

              // Set Active label
              /*
              $j('li').click(function(){
                  if (!$j(this).hasClass('active')){
                      $j(this).parent().find('li').removeClass('active').find('input').attr('checked', '');
                      $j(this).addClass('active').find('input').attr('checked', 'checked');
                      alert($j(this).html())
                  }
              });
              */

              // Toggle Date Labels
              $j('label[for="start_generic_date"]').click(function() {
                if ($j(this).find('input:checkbox').is(':checked')){
                  $j(this).parents('ul').find('li:first').hide();
                } else {
                  $j(this).parents('ul').find('li:first').show();
                }
              });

              // Change Plan Length
              function setPlanLength(){
                planLength = $j('#plan_length').val();
                $j('.year_details').find('tr').hide();
                for (i=0; i<=planLength; i++){
                  $j('.year_details').find('tr:eq('+i+')').show();
                }
              };
              setPlanLength();
              $j('#plan_length').change(function() {
                setPlanLength();
              });

              $j('li.not-generic').live('click', function () {
                if ($j('input[name=generic]:checked').val() == "true") {
                    window.useGenericStartDate(false);
                }
              });

              $j('input[name=generic]').live('click', function () {
                window.useGenericStartDate(($j('input[name=generic]:checked').val() == "true"));
              });

              window.useGenericStartDate = function (b) {
                  var startYear = $j("li.not-generic input[type=text]");
                  if (startYear.val() < 2000) {
                      startYear.val(2000);
                  }
                  window.useGenericStartDateR(b);
              }

              // Table Financial Detail Toggle
              $j('.year_details table input').click(function() {
                $j(this).parents('tr').find('label').removeClass('checked');
                $j(this).parent().addClass('checked');
                if($j(this).hasClass('total')) {
                  $j(this).parents('tr').nextAll('tr').each(function(){
                    $j(this).find('label.monthly').removeClass('checked');
                    $j(this).find('label.total').addClass('checked');
                    $j(this).find('label.monthly').find('input').attr('checked', '');
                    $j(this).find('label.total').find('input').attr('checked', 'checked');
                  });
                } else {
                  $j(this).parents('tr').prevAll('tr').each(function(){
                    $j(this).find('label.monthly').addClass('checked');
                    $j(this).find('label.total').removeClass('checked');
                    $j(this).find('label.monthly').find('input').attr('checked', 'checked');
                    $j(this).find('label.total').find('input').attr('checked', '');
                  });
                }
              });

              /* Tooltips */

              $j('.financial-detail').tooltip({
                tip: '#financial-detail-tooltip',
                position: 'top center',
                offset: [0, -15],
                effect: 'slide',
                    predelay: 0,
                    direction: 'up',
                events: {
                  def:     'click, blur',
                  tooltip: 'click, click'
                }
                }).dynamic( {
                    bottom: {
                        direction: 'down'
                    }
                });

                /* plan users */
                var updatePlanUsers = function () {
                    var ids = [];
                    $j('.planUsers input[type=checkbox]:checked').each(function () { ids.push(this.value); });
                    $j('#selectedPlanUsers input').val(ids.join(','));
                };
                $j('.planUsers input').live('click', updatePlanUsers);
                updatePlanUsers();

                bpo.trackPage();
                $j('#newPlanForm\\:plan_name').focus();
            });

            jQuery.validator.addMethod("noslashes", function(value, element) {
              return this.optional(element) || /^[^\\\/]*$/i.test(value);
            }, "No slashes please");


            // Validate New Plan form
            $j('#newPlanForm').validate({
              rules: {
                "newPlanForm:plan_name": {
                  required: true,
                  noslashes: true,
                  minlength: 4,
                  maxlength: 60
                }
              },
              messages: {
                "newPlanForm:plan_name": {
                  required: "You must enter a plan name",
                  minlength: "Your plan name must be at least 4 characters long",
                  maxlength: "Your plan name must be no longer than 60 characters long"
                }
              },
              submitHandler: function(form) {
                  $j(form).find('.ajax-submit').click();
              },
              onkeyup: false
            });

            // Expect this variable to get wiped once the post occurs
            var createPlanSubmitToken = false;
            window.isCreatePlanSubmitValid = function() {
                if (!$j('#newPlanForm').valid() || createPlanSubmitToken) {
                    return false;
                } else {
                    createPlanSubmitToken = true;
                    $j('.button-submit').addClass('active');
                    return true;
                }
            };
            //]]>
            