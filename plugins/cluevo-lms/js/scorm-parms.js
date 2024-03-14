var scormErrors = {
  ElementIsReadOnly: 404,
  ElementIsWriteOnly: 405,
  ElementNotInitialized: 403,
  GetValueBeforeInit: 112,
  SetValueBeforeInit: 132
}

var scormParameterTypes = {
  'cmi._version': {type: 'string', mode: 'ro', default: '2004'}, // (characterstring, RO)
  'cmi.completion_status': {
    type: 'string',
    mode: 'rw',
    values: ['completed', 'incomplete', 'not attempted', 'unknown'],
    default: 'unknown'
  }, // (“completed”, “incomplete”, “not attempted”, “unknown”, RW)
  'cmi.completion_threshold': {type: 'real', mode: 'rw'}, // (real(10,7)
  'cmi.credit': {type: 'string', mode: 'ro', values: ['credit', 'no-credit'], default: 'credit' }, // (“credit”, “no-credit”, RO)
  'cmi.entry': {type: 'string', mode: 'ro', values: ['ab_initio', 'resume'], default: 'resume' }, // (ab_initio, resume, “”, RO)
  'cmi.exit': {
    type: 'string',
    mode: 'wo',
    values: ['timeout', 'suspend', 'logout', 'normal', ''],
  }, // (timeout, suspend, logout, normal, “”, WO)
  'cmi.launch_data': {type: 'string', mode: 'rw'}, // (characterstring (SPM: 4000)
  'cmi.learner_id': {type: 'string', mode: 'rw'}, // (long_identifier_type (SPM: 4000)
  'cmi.learner_name': {type: 'string', mode: 'rw'}, // (localized_string_type (SPM: 250)
  'cmi.location': {type: 'string', mode: 'rw'}, // (characterstring (SPM: 1000)
  'cmi.max_time_allowed': {type: 'real', digits: [10, 2]}, // (timeinterval (second,10,2)
  'cmi.mode': {
    type: 'string',
    mode: 'ro',
    values: ['browse', 'normal', 'review'],
    default: "normal"
  }, // (“browse”, “normal”, “review”, RO)
  'cmi.progress_measure': {type: 'real', mode: 'rw', digits: [10, 7]}, // (real (10,7)
  'cmi.scaled_passing_score': {type: 'real', mode: 'rw', digits: [10, 7]}, // (real(10,7)
  //'cmi.score._children': '', // (scaled,raw,min,max, RO)
  'cmi.score.scaled': {type: 'real', mode: 'rw', digits: [10, 7]}, // (real (10,7)
  'cmi.score.raw': {type: 'real', mode: 'rw', digits: [10, 7]}, // (real (10,7)
  'cmi.score.min': {type: 'real', mode: 'rw', digits: [10, 7]}, // (real (10,7)
  'cmi.score.max': {type: 'real', mode: 'rw', digits: [10, 7]}, // (real (10,7)
  'cmi.session_time': {type: 'real', mode: 'rw', digits: [10, 2]}, // (timeinterval (second,10,2)
  'cmi.success_status': {
    type: 'string',
    mode: 'rw',
    values: ['passed', 'failed', 'unknown'],
    default: 'unknown'
  }, // (“passed”, “failed”, “unknown”, RW)
  'cmi.suspend_data': {type: 'string', mode: 'rw'}, // (characterstring (SPM: 64000)
  'cmi.time_limit_action': {
    type: 'string',
    mode: 'ro',
    values: [
      'exit,message',
      'continue,message',
      'exit,no message',
      'continue,no message',
    ],
    default: 'exit,no message'
  }, // (“exit,message”, “continue,message”, “exit,no message”, “continue,no message”, RO)
  'cmi.total_time': {type: 'real', mode: 'rw', digits: [10, 2]}, // (timeinterval (second,10,2)
};
