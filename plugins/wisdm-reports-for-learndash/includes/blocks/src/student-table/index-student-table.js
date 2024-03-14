import "./index.scss";

import WisdmLoader from "../commons/loader/index.js";
import React, { Component } from "react";
import { __ } from "@wordpress/i18n";
import WisdmFilters from "../commons/filters/index.js";
import { useTable, usePagination } from "react-table";
import Modal, { closeStyle } from "simple-react-modal";
import StudentFilters from "../student-filters/index-student-filters.js";

// Custom component to render Genres
const CustomHtml = ({ values }) => {
  // Loop through the array and create a badge-like component instead of a comma-separated string
  return (
    <>{<span dangerouslySetInnerHTML={{ __html: decodeURI(values) }}></span>}</>
  );
};

// Custom component to render Genres
const CustomDiv = ({ values }) => {
  // Loop through the array and create a badge-like component instead of a comma-separated string
  return (
    <>
      {
        <div dangerouslySetInnerHTML={{ __html: values }}></div>
      }
    </>
  );
};

function expandTableRow(evnt,i) {
  
  var div = jQuery("#myrow_"+i).toggle(100);
  jQuery("#myrow_"+i).toggleClass( "student-dashboard-row-border-change-r2" )
  jQuery("#my_parent_row_"+i).toggleClass( "student-dashboard-row-border-change" );
}

function questionSelected(evnt) {
  var div = jQuery(evnt.target);
  var qno = div.html();
  console.log(div.html());
  const applyFilters = new CustomEvent("wisdm-ld-question-clicked", {
    detail: {
      question_detail: jQuery(div).attr("data-question"),
      attempt_detail: jQuery(div).attr("data-attempt"),
      all_questions: jQuery(div).attr("data-allquestion"),
      q_no: qno
    },
  });
  document.dispatchEvent(applyFilters);
  jQuery(".question_detail_modal").trigger("click");
}

function Table({ columns, data }) {
  // Use the state and functions returned from useTable to build your UI
  const {
    getTableProps,
    getTableBodyProps,
    headerGroups,
    prepareRow,
    page, // Instead of using 'rows', we'll use page,
    // which has only the rows for the active page

    // The rest of these things are super handy, too ;)
    canPreviousPage,
    canNextPage,
    pageOptions,
    pageCount,
    gotoPage,
    nextPage,
    previousPage,
    setPageSize,
    state: { pageIndex, pageSize },
  } = useTable(
    { columns, data, initialState: { pageIndex: 0 } },
    usePagination
  );


  // Render the UI for your table
  return (
    <>
      <div className="course-reports-wrapper">
        <div className="course-table-wrap">
          <table className="course-list-table student-table" {...getTableProps()}>
            <thead>
              {headerGroups.map((headerGroup) => (
                <tr {...headerGroup.getHeaderGroupProps()}>
                  {headerGroup.headers.map((column) => (
                    <th
                      {...column.getHeaderProps()}
                      className={column.className}
                    >
                      {column.render("Header")}
                    </th>
                  ))}
                </tr>
              ))}
            </thead>
            <tbody {...getTableBodyProps()}>
              {page.map((row, i) => {
                prepareRow(row);
                return (
                  <>
                  <tr id={"my_parent_row_"+i} className="course-list-table-data-row student-dashboard-table-row"{...row.getRowProps()}>
                    {row.cells.map((cell) => {
                      return (
                          <td className={cell.column.className} id="custom-reports" {...cell.getCellProps()}>
                            {(cell.column.Header == "") ? (
                                <span class="accordion-trigger" onClick={(event)=>{expandTableRow(event,i)}} data-cname={JSON.stringify(
                                cell.column.className
                              )}></span>
                            
                              ) : 
                              cell.render("Cell")
                            }
                          </td>
                      )
                    })}
                  </tr>
                  <tr className="course-list-table-data-row myrow_" id={"myrow_"+i} {...row.getRowProps()}>
                  {row.cells.map((cell) => {
                    return (
                        <>
                        {cell.column.Header == "" ? (
                          <td className={cell.column.className} {...cell.getCellProps()} colspan="9">
                            <div className="question-container">
                              <div className="question-head">
                                <div className="question-count">
                                  <strong>
                                   
                                    {__("List of ", "learndash-reports-by-wisdmlabs")}
                                    {cell.row.original.questions.length}{" "}
                                    {__("Questions", "learndash-reports-by-wisdmlabs")}
                                    
                                  </strong>
                                </div>
                                <div className="answer-status">
                                  <strong>{__("Answer Status:", "learndash-reports-by-wisdmlabs")} </strong>
                                  <span className="list markcorrect">
                                    
                                    {__("Correct", "learndash-reports-by-wisdmlabs")}
                                  </span>
                                  <span className="list markincorrect">
                                    
                                    {__("Incorrect", "learndash-reports-by-wisdmlabs")}
                                  </span>
                                </div>
                              </div>
                              <div className="question-body">
                                <ul>
                                  {" "}
                                  {cell.row.original.questions.map(
                                    (questionData, i) => {
                                      let tilecolor =
                                        parseInt(questionData.qspoints) > 0
                                          ? "correct"
                                          : "incorrect";
                                      return (
                                        <li
                                          className={tilecolor}
                                          data-question={JSON.stringify(
                                            questionData
                                          )}
                                          data-attempt={JSON.stringify(
                                            cell.row.original
                                          )}
                                          data-allquestion={JSON.stringify(
                                            cell.row.original.questions
                                          )}
                                          onClick={questionSelected}
                                        >
                                          {i + 1}
                                        </li>
                                      );
                                    }
                                  )}
                                </ul>
                              </div>
                            </div>
                          </td>
                        ) : ''}
                      </>
                    );
                  })}
                  </tr>
                  </>
                );
              })}
            </tbody>
          </table>
        </div>
        {/* 
        Pagination can be built however you'd like. 
        This is just a very basic UI implementation:
      */}
        {/* <div className="table-pagination">
        <button onClick={() => gotoPage(0)} disabled={!canPreviousPage}>
          {"<<"}
        </button>{" "}
        <button onClick={() => previousPage()} disabled={!canPreviousPage}>
          {"<"}
        </button>{" "}
        <span>
          {__('Page', 'learndash-reports-by-wisdmlabs') + " "}
          <strong>
            {pageIndex + 1}  {' ' + __('Of', 'learndash-reports-by-wisdmlabs') + ' ' }  {pageOptions.length}
          </strong>{" "}
        </span>
        <button onClick={() => nextPage()} disabled={!canNextPage}>
          {">"}
        </button>{" "}
        <button onClick={() => gotoPage(pageCount - 1)} disabled={!canNextPage}>
          {">>"}
        </button>{" "}
      </div> */}
      </div>
    </>
  );
}

class StudentTable extends Component {
  constructor(props) {
    super(props);
    let error = null;
    if (this.getUserType()) {
      error = {
        message: __(
          "Sorry you are not allowed to access this block, please check if you have proper access permissions",
          "learndash-reports-by-wisdmlabs"
        ),
      };
    }
    this.state = {
      isLoaded: true,
      isQuestionLoaded: false,
      error: error,
      tableHeaders: [],
      tableData: [],
      start_date: moment(
        new Date(wisdm_ld_reports_common_script_data.start_date)
      ).unix(),
      end_date: moment(
        new Date(wisdm_ld_reports_common_script_data.end_date)
      ).unix(),
      user_id:
        wisdm_learndash_reports_front_end_script_student_table.current_user
          .ID,
      show_question_detail_modal: false,
      questionData: {},
      attemptData: {},
      allQuestions:[],
      question_rendered: "",
      page: 1,
      entries: 0,
      course: null,
      quiz: null,
    };

    this.applyFilters = this.applyFilters.bind(this);
    this.addMoreData = this.addMoreData.bind(this);
    this.questionDetailsPopulate = this.questionDetailsPopulate.bind(this);
    this.closeQuestionModal = this.closeQuestionModal.bind(this);
    this.openQuestionModal = this.openQuestionModal.bind(this);
    this.changePageEvent = this.changePageEvent.bind(this);
  }

  changePageEvent() {
    let self = this;
    jQuery(document).on(
      "click",
      ".pagination-section a.page-numbers",
      function (evnt) {
        evnt.preventDefault();
        let page = jQuery(this).attr("data-page");
        self.setState({
          page: page,
        });
        let request_url =
          "/rp/v1/student-dashboard-info/?start_date=" +
          self.state.start_date +
          "&end_date=" +
          self.state.end_date +
          "&course_id=" +
          self.state.course +
          "&quiz_id=" +
          self.state.quiz +
          "&user_id=" +
          self.state.user_id +
          "&page=" +
          page;
        self.getStudentQuizData(request_url);
      }
    );
    jQuery(document).on(
      "click",
      ".pagination-section .previous-page",
      function (evnt) {
        evnt.preventDefault();
        if (jQuery(this).hasClass("disabled")) {
          return true;
        }
        let next = self.state.page - 1;
        self.setState({ page: next });
        let request_url =
          "/rp/v1/student-dashboard-info/?start_date=" +
          self.state.start_date +
          "&end_date=" +
          self.state.end_date +
          "&course_id=" +
          self.state.course +
          "&quiz_id=" +
          self.state.quiz +
          "&user_id=" +
          self.state.user_id +
          "&page=" +
          next;
        self.getStudentQuizData(request_url);
      }
    );
    jQuery(document).on(
      "click",
      ".pagination-section .next-page",
      function (evnt) {
        evnt.preventDefault();
        if (jQuery(this).hasClass("disabled")) {
          return true;
        }
        let next = self.state.page + 1;
        self.setState({ page: next });
        let request_url =
          "/rp/v1/student-dashboard-info/?start_date=" +
          self.state.start_date +
          "&end_date=" +
          self.state.end_date +
          "&course_id=" +
          self.state.course +
          "&quiz_id=" +
          self.state.quiz +
          "&user_id=" +
          self.state.user_id +
          "&page=" +
          next;
        self.getStudentQuizData(request_url);
      }
    );
  }

  closeQuestionModal() {
    document.body.classList.remove("wrld-open");
    this.setState({
      show_question_detail_modal: false,
    });
  }

  openQuestionModal() {
    document.body.classList.add("wrld-open");
    this.setState({
      show_question_detail_modal: true,
    });
  }

  /**
   * Based on the current user roles aray this function desides wether a user is a group
   * leader or an Administrator and returns the same.
   */
  getUserType() {
    return wisdm_learndash_reports_front_end_script_student_table.current_user.ID == 0;
  }

  questionDetailsPopulate(event) {
    var questionData = JSON.parse(event.detail.question_detail);
    var attemptData = JSON.parse(event.detail.attempt_detail);
    var allQuestions = JSON.parse(event.detail.all_questions);
    var q_no = JSON.parse(event.detail.q_no);
    this.setState({ questionData: questionData, attemptData: attemptData ,allQuestions:allQuestions,isQuestionLoaded:false,question_rendered:''});
    var requestUrl = "/rp/v1/question-details/?question_data=" + event.detail.question_detail+"&q_no="+q_no;
    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: requestUrl,
    }).then((response) => {
      this.setState({ question_rendered: response.table ,isQuestionLoaded:true});
    });
  }

  applyFilters(event) {
    let start_date = event.detail.start_date;
    let end_date = event.detail.end_date;
    let course = event.detail.selected_courses.value;
    let quiz = event.detail.selected_quiz.value;
    let user_id = event.detail.user_id;
    let courses = event.detail.courses;
    let quizzes = event.detail.quizzes;
    this.setState({
      start_date: start_date,
      end_date: end_date,
      course: course,
      quiz: quiz,
      user_id: user_id,
      page: 1,
      courses: courses,
      quizzes: quizzes,
      course_label: event.detail.selected_courses.label,
      quiz_label: event.detail.selected_quiz.label,
    });
    let request_url =
      "/rp/v1/student-dashboard-info/?start_date=" +
      start_date +
      "&end_date=" +
      end_date +
      "&course_id=" +
      course +
      "&quiz_id=" +
      quiz +
      "&user_id=" +
      user_id +
      "&page=1";
    this.getStudentQuizData(request_url);
  }

  addMoreData(evnt) {
    let next = this.state.page + 1;
    this.setState({ page: next });
    let request_url =
      "/rp/v1/student-dashboard-info/?start_date=" +
      this.state.start_date +
      "&end_date=" +
      this.state.end_date +
      "&course_id=" +
      this.state.course +
      "&quiz_id=" +
      this.state.quiz +
      "&user_id=" +
      this.state.user_id +
      "&page=" +
      next;
    this.getStudentQuizData(request_url);
  }

  componentDidMount() {
    let request_url =
      "/rp/v1/student-dashboard-info/?start_date=" +
      this.state.start_date +
      "&end_date=" +
      this.state.end_date +
      "&course_id=" +
      null +
      "&quiz_id=" +
      null +
      "&user_id=" +
      this.state.user_id +
      "&page=1";

    this.getStudentQuizData(request_url);
    this.changePageEvent();
    document.addEventListener(
      "wisdm-ld-reports-student-filters-applied",
      this.applyFilters
    );
    document.addEventListener(
      "wisdm-ld-question-clicked",
      this.questionDetailsPopulate
    );
  }

  componentDidUpdate() {}

  getStudentQuizData(request_url = "/rp/v1/course-list-info") {
    jQuery('.button-quiz-preview').css({'cursor': 'progress'});
    this.setState({
      isLoaded: false,
    });
    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
      request_url += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: request_url,
    })
      .then((response) => {
        var table = response.table;
        if (undefined == response) {
          table = [];
        }
        jQuery('.button-quiz-preview').css({'cursor': 'pointer'});
        this.setState({
          isLoaded: true,
          isProVersion:
            wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active,
          tableHeaders: this.getTableHeadersByType(response.table),
          tableData: this.getTableData(response.table),
          request_data: response.requestData,
          entries: response.total,
        });
        const applyFilters = new CustomEvent("wisdm-ld-table-reloaded", {
          detail: {
            parent_state: this.state
          },
        });
        document.dispatchEvent(applyFilters);

      })
      .catch((error) => {
        this.setState({
          error: error,
          isLoaded: true,
          request_data: error.data.requestData,
        });
      });
  }

  getTableData(response) {
    let newTabledata = [];
    response.map((columns) => {
      let totalpoint = 0;
      let earned_point = 0;
      let total_time = 0;
      let score_in_percntge = "";
      columns.questions.map((question) => {
        totalpoint = totalpoint + parseInt(question.points, 10);
        earned_point = earned_point + parseInt(question.qspoints, 10);
        total_time = total_time + parseInt(question.question_time, 10);
      });
      score_in_percntge =
        totalpoint == 0
          ? 0
          : ((earned_point / totalpoint) * 100).toFixed(2) + "%";

      total_time = new Date( total_time * 1000 ).toISOString().slice(11, 19);    

      newTabledata.push({
        ...columns,
        total_time: total_time,
        total_point: totalpoint,
        score_prcentage: score_in_percntge,
        earned_point: earned_point,
        first_col: "",
      });
    });
    return newTabledata;
  }

  getTableHeadersByType(response) {
    let headers = [];
    headers.push({
      Header: "",
      accessor: "first_col",
      className: "table-first_col",
    });
    headers.push({
      Header: __('Quiz title', "learndash-reports-by-wisdmlabs"),
      accessor: "quiz_title",
      className: "table-quiz_title",
      Cell: ({ cell: { value } }) => <CustomHtml values={value} />,
    });
    headers.push({
      Header:  __('Course title', "learndash-reports-by-wisdmlabs"),
      accessor: "course_title",
      className: "table-course_title",
    });
    headers.push({
      Header:  __('Quiz Category', "learndash-reports-by-wisdmlabs"),
      accessor: "quiz_category",
      className: "table-quiz_category",
    });
    headers.push({
      Header: __('Points Earned', "learndash-reports-by-wisdmlabs"),
      accessor: "earned_point",
      className: "table-earned_point",
    });
    headers.push({
      Header: __('Score in %', "learndash-reports-by-wisdmlabs"),
      accessor: "score_prcentage",
      className: "table-score_prcentage",
    });
    headers.push({
      Header:  __('Date of Attempt', "learndash-reports-by-wisdmlabs"),
      accessor: "date_attempt",
      className: "table-date_attempt",
    });
    headers.push({
      Header: __('Time Taken', "learndash-reports-by-wisdmlabs"),
      accessor: "total_time",
      className: "table-total_time",
    });
    headers.push({
      Header:  __('Download', "learndash-reports-by-wisdmlabs"),
      accessor: "links",
      Cell: ({ cell: { value } }) => <CustomHtml values={value} />,
      className: "table-links",
    });
    return headers;
  }

  render() {
    let body = <div></div>;
    let question_type_map = {
      single: __("Single choice", "learndash-reports-by-wisdmlabs"),
      multiple: __("Multiple choice", "learndash-reports-by-wisdmlabs"),
      free_answer: __('Free choice', "learndash-reports-by-wisdmlabs"),
      sort_answer: __('Sorting choice', "learndash-reports-by-wisdmlabs"),
      matrix_sort_answer: __(
        '"Matrix Sorting" choice',
        "learndash-reports-by-wisdmlabs"
      ),
      cloze_answer: __("Fill in the blank", "learndash-reports-by-wisdmlabs"),
      assessment_answer: __("Assessment", "learndash-reports-by-wisdmlabs"),
      essay: __("Essay / Open Answer", "learndash-reports-by-wisdmlabs"),
    };
    if (this.state.error) {
      body = <div class="ldrp-nodata-container wrld-error"><div><strong>  {__( "Access Denied.", "learndash-reports-by-wisdmlabs" )} </strong>{__( "You need to be logged in to access this page.", "learndash-reports-by-wisdmlabs" )}</div></div>;
    } else if (!this.state.isLoaded) {
      // yet loading
      body = <WisdmLoader />;
    } else {
      let table_data = this.state.tableData;
      let table_headers = [];
      let table_parsed_data = [];

      for (
        var itrator = 0;
        itrator < this.state.tableHeaders.length;
        itrator++
      ) {
        table_headers[itrator] = this.state.tableHeaders[itrator]["Header"];
      }
      let pages = Math.ceil(this.state.entries / 10);
      let page_divs = '<div class="pagination-section">';
      if (pages > 1) {
        page_divs =
          page_divs +
          '<a href="#" class="previous-page button ' +
          (this.state.page == 1 ? "disabled" : "") +
          '">' +
          __("Previous", "learndash-reports-by-wisdmlabs") +
          "</a>";
        for (let i = 1; i <= pages; i++) {
          if (this.state.page == i) {
            page_divs =
              page_divs +
              '<span aria-current="page" class="page-numbers current">' +
              this.state.page +
              "</span>";
          } else {
            page_divs =
              page_divs +
              '<a class="page-numbers" data-page=' +
              i +
              ' href="#">' +
              i +
              "</a>";
          }
        }
        page_divs =
          page_divs +
          '<a href="#" class="next-page button ' +
          (this.state.page == pages ? "disabled" : "") +
          '">' +
          __("Next", "learndash-reports-by-wisdmlabs") +
          "</a>";
      }
      page_divs = page_divs + "</div>";
      body = (
        <div className="user-info-section">
          <StudentFilters parent={this.state}/>
          <div className="wisdm-learndash-reports-chart-block">
            <div className="wisdm-learndash-reports-course-list table-chart-container">
              <div className="course-list-table-container">
                <WisdmFilters request_data={this.state.request_data} />
                <div className="course-list-table-header">
                  <div className="chart-title">
                    <span>
                      {__("Detailed", "learndash-reports-by-wisdmlabs") +
                        " " +
                        wisdm_reports_get_ld_custom_lebel_if_avaiable("Quiz") +
                        " " +
                        __("Reports", "learndash-reports-by-wisdmlabs")}
                    </span>
                  </div>
                </div>
                { ( this.state.tableData.length > 0 ) ? (
                  <Table
                    columns={this.state.tableHeaders}
                    data={this.state.tableData}
                  />
                ) : (
                  <div>
                    <Table
                      columns={this.state.tableHeaders}
                      data={this.state.tableData}
                    />
                    <div className="error-message">
                      <span>
                        {__("No Quiz attempts for the selected filters.", "learndash-reports-by-wisdmlabs")}
                      </span>
                    </div>
                  </div>
                )}
                <CustomDiv values={page_divs} />
              </div>
            </div>
          </div>
          <Modal
            show={this.state.show_question_detail_modal}
            onClose={this.closeQuestionModal}
            containerStyle={{ width: "50%" }}
            className={"bulk_export_modal"}
          >
            <span
              className="close-modal dashicons dashicons-no"
              onClick={this.closeQuestionModal}
            ></span>
            <div className="qre-question-container question-detail-modal student-dash">
              <h2>{__("Question Response Report", "learndash-reports-by-wisdmlabs")}</h2>
              <div className="question-details">
                <div className="outer-1">
                  <div className="inner-1">
                    <div className="user-detail">
                      <div className="username">
                        <span>
                          <strong> {__("User Name: ", "learndash-reports-by-wisdmlabs")}</strong>
                          <br />
                          {
                            wisdm_learndash_reports_front_end_script_student_table
                              .current_user.data.display_name
                          }
                        </span>
                      </div>
                      <div className="quiz-title">
                        <span>
                          <strong>{__("Quiz Title: ", "learndash-reports-by-wisdmlabs")}</strong>
                          <br />
                          <CustomHtml
                            values={this.state.attemptData.quiz_title}
                          />
                        </span>
                      </div>
                    </div>
                    <div>
                      <span>
                        <strong> {__("Score(in %): ", "learndash-reports-by-wisdmlabs")}</strong>
                        <br />
                        {this.state.attemptData.score_prcentage}
                      </span>
                    </div>
                  </div>
                </div>
                <br />
                <hr />
                <div className="outer-2 questioninfo">
                  <div className="question-type">
                    <strong>
                    {__("Question Type :", "learndash-reports-by-wisdmlabs")}  {this.state.questionData.length}
                    </strong>
                    <br />
                    <span>{question_type_map[this.state.questionData.answer_type]}</span>
                  </div>
                  <div className="question-category">
                    <strong> {__("Question Category: ", "learndash-reports-by-wisdmlabs")}</strong>
                    <br />
                    {this.state.attemptData.quiz_category}
                  </div>
                </div>
                <div className="outer-3">
                  <div className="learndash">
                    <div className="learndash-wrapper">
                      <div className="wpProQuiz_content">
                        <div className="wpProQuiz_quiz">
                          { this.state.isQuestionLoaded ? <CustomDiv values={this.state.question_rendered} /> : <div className="wisdm-question-loader"><WisdmLoader /></div> }
                         
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="question-navigation question-body">
              <ul>
                {this.state.allQuestions.map(
                                      (questionData, i) => {
                                        let tilecolor =
                                          parseInt(questionData.qspoints) > 0
                                            ? "correct"
                                            : "incorrect";
                                        return (
                                          <li
                                            className={tilecolor}
                                            data-question={JSON.stringify(
                                              questionData
                                            )}
                                            data-allquestion={JSON.stringify(
                                              this.state.allQuestions
                                            )}
                                            data-attempt={JSON.stringify(
                                             this.state.attemptData
                                            )}
                                            onClick={questionSelected}
                                          >
                                            {i + 1}
                                          </li>
                                        );
                                      }
                                    )}
                                    </ul>
              </div>
            </div>
          </Modal>
          <button
            className="question_detail_modal"
            onClick={this.openQuestionModal}
            style={{ display: "none" }}
          >
            {__("Question Response", "learndash-reports-by-wisdmlabs")}
          </button>
        </div>
      );
    }
    return body;
  }
}

export default StudentTable;

document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName(
    "wisdm-learndash-reports-student-table front"
  );
  if (elem.length > 0) {
    ReactDOM.render(React.createElement(StudentTable), elem[0]);
  }
});
