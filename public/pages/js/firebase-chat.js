import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
import { getCountFromServer, getFirestore, setDoc, addDoc, doc, collection, updateDoc, deleteDoc, onSnapshot, query, getDocs, or, where, getDoc, limit, and, serverTimestamp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore.js";

const fpPromise = import('https://openfpcdn.io/fingerprintjs/v4')
    .then(FingerprintJS => FingerprintJS.load())

fpPromise
    .then(fp => fp.get())
    .then(result => {
    // This is the visitor identifier:
    const firebaseConfig = {
        apiKey: "AIzaSyB6AwYq5DNv8fqWTMxCqyU_sAp--id9aMM",
        authDomain: "dental-clinic-3ccac.firebaseapp.com",
        projectId: "dental-clinic-3ccac",
        storageBucket: "dental-clinic-3ccac.appspot.com",
        messagingSenderId: "563628572047",
        appId: "1:563628572047:web:ce6dbc3fa7b5baa337dffa",
        measurementId: "G-HEX3P0G7S7"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    var userId = document.querySelector('meta[name="user-id"]').content;
    var visitorId = 0
    if(userId > 0){
        visitorId = userId;
    }else{
        visitorId = result.visitorId;
    }

    sessionStorage.setItem("visitorId",visitorId);

    const messagesRef = collection(db, "messages");
    const q = query(messagesRef, 
        where("visitor_id", "==", visitorId),
        limit(1)
    );

    console.log("Visitor ID: "+ visitorId);

    let counter = 0;

    const unsubscribe = onSnapshot(q, (querySnapshot) => {
        querySnapshot.forEach((doc) => {
            counter +=1;
            getMessageThread(doc.id);
            sessionStorage.setItem('messageId', doc.id);
        });
        setTimeout(function () {
            createMessage(counter, visitorId);
        }, 2000);
    });

    let thread = [];

    const createMessage = async(counter, visitorId) => {
        
        if(counter == 0){
            let message = "Hi! 👋 How can I help you?";
            let senderId = "admin";

            const initialMessage = [
                {sent_at: Date.now(), sender_id: senderId, message: message }
            ];

            const docRef = await addDoc(collection(db, "messages"), {
                visitor_id: visitorId,
                thread: initialMessage
            });
            console.log("MESSAGE ID: "+docRef.id);

            updateDoc(doc(db, "messages", docRef.id), {
                id: docRef.id
            });
            
            getMessageThread(docRef.id);
            sessionStorage.setItem('messageId', docRef.id);
        }        
    }

    function getMessageThread(messageId){
        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        const visitorId = sessionStorage.getItem('visitorId')

        const unsubscribe = onSnapshot(q, (querySnapshot) => {
            querySnapshot.forEach((doc) => {
                thread = doc.data().thread;
            });
            if(thread){
                
                $('.chat-body').empty();
                thread.forEach(function(item) {
                    let d = item.sent_at;
                    var time = moment(d).format('hh:mm a - L');

                    let position = '';

                    if(item.sender_id == visitorId){
                        $('.chat-body').append(`
                            <div class="chat-item">
                                <div class="chat-right">
                                    ${item.message}
                                </div>
                            </div>
                        `);
                    }else{
                        $('.chat-body').append(`
                            <div class="chat-item">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <img src="/vet-clinic/images/face0.jpg" class="chat-image chat-image-left" alt="avatar">
                                    </div>
                                    <div class="col-xs-10 pl-0">
                                        <div class="chat-left">
                                            ${item.message}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                });
                
                var body = document.querySelector('.chat-body');
                let height = 0;
                if(body.scrollHeight == 0){
                    height = 500;
                }else{
                    height = body.scrollHeight;
                }
                
                body.scrollTop = height;
            }
            
        });
    }

    const send = document.getElementById("btn-send");

    send.addEventListener("click", function() {
        let messageId = sessionStorage.getItem('messageId')
        sendMessage(messageId);
    });

    function sendMessage(messageId){

        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        const unsubscribe = onSnapshot(q, (querySnapshot, thread) => {
            querySnapshot.forEach((doc) => {
                thread = doc.data().thread;
            });
        });

        let inputMessage = document.getElementById("input-message").value;
        if(!thread){
            thread = []
        }
        if(inputMessage!=''){
            const newThreadMessage = [
                {sent_at: Date.now(), sender_id: visitorId, message: inputMessage }
            ];

            const allMessage = [...thread, ...newThreadMessage];

            updateDoc(doc(db, "messages", messageId), {
                thread: allMessage
            });

            setInterval(automaticReply(allMessage, inputMessage), 2000);
            document.getElementById("input-message").value = '';
        }
    }

    function automaticReply(thread, message){
        let messageId = sessionStorage.getItem('messageId')
        
        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        let inputMessage = message;
        if(!thread){
            thread = []
        }
        let senderId = 'admin';

        if(inputMessage!=''){
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var autoReply = document.querySelector('meta[name="auto-reply-url"]').content;

            $.ajax({
                type: "POST",
                url: autoReply,
                data: { _message : inputMessage , _token : csrf  },
                dataType: "json",
                async: true,
                success: function(data){
                    // console.log(data.datas.answer);
                    var answer = data.datas.answer;
                    const newThreadMessage = [
                        {sent_at: Date.now(), sender_id: senderId, message: answer }
                    ];

                    const allMessage = [...thread, ...newThreadMessage];

                    updateDoc(doc(db, "messages", messageId), {
                        thread: allMessage
                    });
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    }
    
    $(".faq-item").on("click", function(){
        let question = $(this).data('question');
        let answer = $(this).data('answer');

        let messageId = sessionStorage.getItem('messageId')
        let request = {
            question:  question,
            answer:  answer
        }

        sendQuestion(messageId, request);
        
        $(".faq-box").css("display", "none");
        
        setTimeout(function(){
            sendAnswer(messageId, request);
        }, 2000);

    });

    function sendQuestion(messageId, request){
        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        const unsubscribe = onSnapshot(q, (querySnapshot, thread) => {
            querySnapshot.forEach((doc) => {
                thread = doc.data().thread;
            });
        });

        let inputMessage = request.question;
        if(!thread){
            thread = []
        }

        if(inputMessage!=''){
            const newThreadMessage = [
                {sent_at: Date.now(), sender_id: visitorId, message: inputMessage }
            ];

            const allMessage = [...thread, ...newThreadMessage];

            updateDoc(doc(db, "messages", messageId), {
                thread: allMessage
            });
        }
    }

    function sendAnswer(messageId, request){
        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        const unsubscribe = onSnapshot(q, (querySnapshot, thread) => {
            querySnapshot.forEach((doc) => {
                thread = doc.data().thread;
            });
        });

        let inputMessage = request.answer;
        let senderId = 'admin';

        if(!thread){
            thread = []
        }

        if(inputMessage!=''){
            const newThreadMessage = [
                {sent_at: Date.now(), sender_id: senderId, message: inputMessage }
            ];

            const allMessage = [...thread, ...newThreadMessage];

            updateDoc(doc(db, "messages", messageId), {
                thread: allMessage
            });
        }
    }
    
    $("#book-appointment-btn").on("click", function(){
        let name = $("#book-name");
        let email = $("#book-email");
        let contact = $("#book-contact");
        let date = $("#book-date");
        let details = $("#book-details");
        
        if(name.val() == ''){
            name.addClass("error-input");
        }else{ name.removeClass("error-input"); }
        if(email.val() == ''){
            email.addClass("error-input");
        }else{ email.removeClass("error-input"); }
        if(contact.val() == ''){
            contact.addClass("error-input");
        }else{ contact.removeClass("error-input"); }
        if(date.val() == ''){
            date.addClass("error-input");
        }else{ date.removeClass("error-input"); }
        if(details.val() == ''){
            details.addClass("error-input");
        }else{ details.removeClass("error-input"); }

        if(name.val() != '' || email.val() != '' || contact.val() != '' || date.val() != '' || details.val() != ''){

            let request = {
                name: name.val(),
                email: email.val(),
                contact: contact.val(),
                date: date.val(),
                details: details.val()
            }

            let messageId = sessionStorage.getItem('messageId');

            bookAppointment(messageId, request);
            clearFields();
        }
    });
    
    function clearFields(){
        $("#book-name").val('');
        $("#book-email").val('');
        $("#book-contact").val('');
        $("#book-date").val('');    
        $("#book-details").val('');
    }
    const bookAppointment = async(messageId, request) => {
        sendBook(messageId, request);

        const coll = collection(db, "appointments");
        const q = query(coll, 
            where("email", "==", request.email),
            where("status", "==", 'pending')
        );

        const snapshot = await getCountFromServer(q);
        let count = snapshot.data().count;
        console.log("Count:"+count);

        if(count > 0){
            bookExist(messageId, request);
        }else{
            book(messageId, request);
        }

        $(".book-box").css("display", "none");
    }

    const book = async(messageId, request) => {
        const docRef = await addDoc(collection(db, "appointments"), {
            name: request.name,
            email: request.email,
            contact: request.contact,
            date: request.date,
            details: request.details,
            status: 'pending'
        });

        updateDoc(doc(db, "appointments", docRef.id), {
            id: docRef.id
        });

        bookConfirmation(messageId, request);
    }

    function bookConfirmation(messageId, request){
        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        const unsubscribe = onSnapshot(q, (querySnapshot, thread) => {
            querySnapshot.forEach((doc) => {
                thread = doc.data().thread;
            });
        });

        let inputMessage = `Hi! 👋 ${request.name}, thank you for your appointment request. Please make your line available for your appointment confirmation. 
                            Please contact our phone # +639167853693 if you want to follow up, reschedule or cancel your appointment.`;
        let senderId = 'admin';
        if(!thread){
            thread = []
        }

        if(inputMessage!=''){
            const newThreadMessage = [
                {sent_at: Date.now(), sender_id: senderId, message: inputMessage }
            ];

            const allMessage = [...thread, ...newThreadMessage];

            updateDoc(doc(db, "messages", messageId), {
                thread: allMessage
            });
        }
    }

    function bookExist(messageId, request){
        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        const unsubscribe = onSnapshot(q, (querySnapshot, thread) => {
            querySnapshot.forEach((doc) => {
                thread = doc.data().thread;
            });
        });

        let inputMessage = `Hi! 👋 ${request.name}, as we're checking our appointment records you still have an outstanding pending appointment request. 
                            Please contact our phone # +639167853693 if you want to reschedule or cancel your appointment.`;
        let senderId = 'admin';
        if(!thread){
            thread = []
        }

        if(inputMessage!=''){
            const newThreadMessage = [
                {sent_at: Date.now(), sender_id: senderId, message: inputMessage }
            ];

            const allMessage = [...thread, ...newThreadMessage];

            updateDoc(doc(db, "messages", messageId), {
                thread: allMessage
            });
        }
    }

    function sendBook(messageId, request){
        const messageRef = collection(db, "messages");
        const q = query(messageRef, 
            where("id", "==", messageId),
            limit(1)
        );

        const unsubscribe = onSnapshot(q, (querySnapshot, thread) => {
            querySnapshot.forEach((doc) => {
                thread = doc.data().thread;
            });
        });

        let inputMessage = `Hello! 👋 I'm ${request.name}. I would like to have "${request.details}" on 
                            ${request.date}. Please email me on ${request.email} or contact me on ${request.contact} to confirm my appointment.`;

        if(!thread){
            thread = []
        }

        if(inputMessage!=''){
            const newThreadMessage = [
                {sent_at: Date.now(), sender_id: visitorId, message: inputMessage }
            ];

            const allMessage = [...thread, ...newThreadMessage];

            updateDoc(doc(db, "messages", messageId), {
                thread: allMessage
            });
        }
    }
});