//
import jQuery from "jquery";
window.$ = jQuery;
import * as Popper from "@popperjs/core";
window.Popper = Popper;
import "bootstrap";

$(".mark_as_non_compliant").click(function () {
    $(".corrective_action_section").toggleClass("d-none");
});

$(".edit-task-btn").click(function () {
    let id = $(this).data("id");
    $('[name="id"]').val(id);
    let editModalEl = $("#editTaskModal");

    $.ajax({
        type: "get",
        url: `/tasks/${id}`,
        data: {},
        success: function (res) {
            console.log(res);

            if (res.success) {
                if (res.task) {
                    console.log(res.task.due_date.split(" ")[0]);

                    editModalEl.find('[name="title"]').val(res.task.title);
                    editModalEl
                        .find('[name="description"]')
                        .val(res.task.description);
                    editModalEl
                        .find('[name="due_date"]')
                        .val(res.task.due_date.split(" ")[0]);
                    editModalEl.find('[name="id"]').val(res.task.id);
                    editModalEl
                        .find('[name="priority"]')
                        .val(res.task.priority);
                    editModalEl
                        .find('[name="assigned_to"]')
                        .val(res.task.assigned_to);
                }
            } else {
                alert("Something went wrong.");
            }
        },
    });
});
$(".change_status").click(function (e) {
    e.preventDefault();
    let taskRowEl = $(this).parents("tr");

    taskRowEl.find(".change_status").attr("disabled", "disabled");
    let formEl = $(this).parents("form");

    let method = formEl.attr("method");
    let url = formEl.attr("action");
    let token = formEl.find('[name="_token"]').val();
    let task_id = formEl.find('[name="task_id"]').val();
    let status = $(this).data("status");
    let corrective_action = $("[name='corrective_action']").val();
    if (status == "non_compliant" && corrective_action == null) {
        return alert("Correction note required.");
    }

    $.ajax({
        type: method,
        url: url,
        data: {
            task_id,
            token,
            status,
            corrective_action,
        },
        success: function (res) {
            if (res.success) {
                if (res.task.status == "completed") {
                    taskRowEl.find(".status").text("Completed");
                } else if (res.task.status == "non_compliant") {
                    taskRowEl.find(".status").text("Non compliant");
                }
                formEl.remove();
            } else {
                alert("Something went wrong.");
            }
        },
    });
});
