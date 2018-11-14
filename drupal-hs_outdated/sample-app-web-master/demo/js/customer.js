$(function () {
    $("#test").delegate("button", "click", function(){
        var type = $(this).attr("data-id");
        switch (type) {
            case "generateSignature":
                let signature = ZoomMtg.generateSignature({
                    apiKey: 'deRfMrjlRPmvSSnXb-Iquw',
                    apiSecret: 'Q7edd04jTSkdr05RC4BLggylIxOiLp9P3ieC',
                    meetingNumber: 611384916,
                    role: 1
                });
                console.log('signature:' + signature);
                break;

            case "init":
                ZoomMtg.init({
                    debug: true, //optional
                    leaveUrl: 'http://localhost/projects/drupal-hs/sample-app-web-master/demo/index.html', //required
                    showMeetingHeader: true, //optional
                    disableInvite: true, //optional
                    disableCallOut: true, //optional
                    disableRecord: true, //optional
                    disableJoinAudio: true //optional
                });
                break;

            case "join":
                ZoomMtg.join({
                    meetingNumber:611384916,
                    userName: 'vivek.gupta@faichi.com',
                    passWord: '123',
                    signature: 'ZGVSZk1yamxSUG12U1NuWGItSXF1dy42MTEzODQ5MTYuMTUwMDk2NjU5ODIwMy4xLi9mQzU4VCswenpwSk80NjJBQ2FnVFFmSHlPMWVKODJuV2w2TThKeGIrV0U9',
                    apiKey: 'deRfMrjlRPmvSSnXb-Iquw'
                });
                break;

            case "getPMI":
                ZoomMtg.getPMI({
                    meetingNumber:611384916,
                    userName: 'vivek.gupta@faichi.com',
                    passWord: '123',
                    signature: 'ZGVSZk1yamxSUG12U1NuWGItSXF1dy42MTEzODQ5MTYuMTUwMDk2NjU5ODIwMy4xLi9mQzU4VCswenpwSk80NjJBQ2FnVFFmSHlPMWVKODJuV2w2TThKeGIrV0U9',
                    apiKey: 'deRfMrjlRPmvSSnXb-Iquw'
                });
                break;

            case "showInviteFunction":
                ZoomMtg.showInviteFunction({
                    show: false
                });
                break;

            case "showCalloutFunction":
                ZoomMtg.showCalloutFunction({
                    show: false
                });
                break;

            case "showMeetingHeader":
                ZoomMtg.showMeetingHeader({
                    show: false
                });
                break;

            case "showRecordFunction":
                ZoomMtg.showRecordFunction({
                    show: false
                });
                break;

            case "showJoinAudioFunction":
                ZoomMtg.showJoinAudioFunction({
                    show: false
                });
                break;

            case "showRecordFunction":
                ZoomMtg.showRecordFunction({
                    show: false
                });
                break;

            case "getAttendeeslist":
                ZoomMtg.getAttendeeslist({});
                break;

            case "callOut":
                ZoomMtg.callOut({
                    phoneNumber: ''
                });
                break;

            case "inviteByPhone":
                ZoomMtg.inviteByPhone({
                    phoneNumber: '',
                    userName: ''
                });
                break;

            case 'mute':
                ZoomMtg.mute({
                    userId: 'dkxT-iIjTuyRyEt6Mfvkzw',
                    mute: true
                });
                break;

            case 'muteAll':
                ZoomMtg.muteAll({
                    muteAll: true
                });
                break;

            case 'rename':
                ZoomMtg.rename({
                    userId: 'dkxT-iIjTuyRyEt6Mfvkzw',
                    oldName: '',
                    newName: ''
                });
                break;

            case 'expel':
                ZoomMtg.expel({
                    userId:'dkxT-iIjTuyRyEt6Mfvkzw'
                });
                break;

            case 'record':
                ZoomMtg.record({
                    record: true
                });
                break;

            case 'lockMeeting':
                ZoomMtg.lockMeeting({
                    lockMeeting: true
                });
                break;

            case 'leaveMeeting':
                ZoomMtg.leaveMeeting({});
                break;

            case 'endMeeting':
                ZoomMtg.endMeeting({});
                break;

            default:
                console.log(">>>>>>default<<<<<<<<<");
        }
    });

});
