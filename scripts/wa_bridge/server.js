const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason,
    Browsers
} = require('@whiskeysockets/baileys');

const express = require('express');
const pino = require('pino');
const qrcode = require('qrcode-terminal');
const path = require('path');

const app = express();

app.use(express.json());

const PORT = 3001;

const AUTH_DIR = path.join(__dirname, 'auth_session');

let sock = null;
let isReady = false;
let reconnecting = false;


/*
|--------------------------------------------------------------------------
| START WHATSAPP
|--------------------------------------------------------------------------
*/

async function startWhatsApp() {

    try {

        const {
            state,
            saveCreds
        } = await useMultiFileAuthState(AUTH_DIR);

        console.log('');
        console.log('Menghubungkan ke WhatsApp...');
        console.log('');

        sock = makeWASocket({

            auth: state,

            logger: pino({
                level: 'silent'
            }),

            browser: Browsers.windows('Chrome'),

            markOnlineOnConnect: false,

            syncFullHistory: false
        });


        /*
        |--------------------------------------------------------------------------
        | SIMPAN SESSION
        |--------------------------------------------------------------------------
        */

        sock.ev.on('creds.update', saveCreds);


        /*
        |--------------------------------------------------------------------------
        | CONNECTION UPDATE
        |--------------------------------------------------------------------------
        */

        sock.ev.on('connection.update', async (update) => {

            const {
                connection,
                lastDisconnect,
                qr
            } = update;


            /*
            |--------------------------------------------------------------------------
            | QR CODE
            |--------------------------------------------------------------------------
            */

            if (qr) {

                console.log('');
                console.log('========================================');
                console.log('       SCAN QR WHATSAPP');
                console.log('========================================');
                console.log('');

                qrcode.generate(qr, {
                    small: true
                });

                console.log('');
                console.log('Buka WhatsApp di HP:');
                console.log('Perangkat tertaut');
                console.log('→ Tautkan perangkat');
                console.log('→ Scan QR di atas');
                console.log('');
            }


            /*
            |--------------------------------------------------------------------------
            | CONNECTING
            |--------------------------------------------------------------------------
            */

            if (connection === 'connecting') {

                console.log('Menghubungkan ke WhatsApp...');
            }


            /*
            |--------------------------------------------------------------------------
            | CONNECTED
            |--------------------------------------------------------------------------
            */

            if (connection === 'open') {

                isReady = true;
                reconnecting = false;

                console.log('');
                console.log('========================================');
                console.log('   WhatsApp berhasil terhubung!');
                console.log('   Bridge siap digunakan.');
                console.log('========================================');
                console.log('');

            }


            /*
            |--------------------------------------------------------------------------
            | CLOSED
            |--------------------------------------------------------------------------
            */

            if (connection === 'close') {

                isReady = false;

                const statusCode =
                    lastDisconnect?.error?.output?.statusCode;

                console.log('');
                console.log('WhatsApp terputus.');
                console.log('Status code:', statusCode);
                console.log('');

                /*
                |--------------------------------------------------------------------------
                | LOGOUT
                |--------------------------------------------------------------------------
                */

                if (statusCode === DisconnectReason.loggedOut) {

                    console.log(
                        'Session logout.'
                    );

                    console.log(
                        'Hapus auth_session lalu jalankan npm start kembali.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | RECONNECT
                |--------------------------------------------------------------------------
                */

                if (!reconnecting) {

                    reconnecting = true;

                    console.log(
                        'Mencoba menghubungkan kembali dalam 5 detik...'
                    );

                    setTimeout(() => {

                        reconnecting = false;

                        startWhatsApp();

                    }, 5000);

                }

            }

        });


    } catch (error) {

        isReady = false;

        console.error('');
        console.error('Gagal menjalankan WhatsApp Bridge:');
        console.error(error);
        console.error('');

    }

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

app.get('/status', (req, res) => {

    res.json({

        success: true,

        ready: isReady,

        port: PORT

    });

});


/*
|--------------------------------------------------------------------------
| SEND MESSAGE
|--------------------------------------------------------------------------
*/

app.post('/send-message', async (req, res) => {

    try {

        const {
            phone,
            message
        } = req.body;


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        if (!phone || !message) {

            return res.status(400).json({

                success: false,

                message: 'Nomor HP dan pesan wajib diisi.'

            });

        }


        /*
        |--------------------------------------------------------------------------
        | CEK WHATSAPP
        |--------------------------------------------------------------------------
        */

        if (!isReady || !sock) {

            return res.status(503).json({

                success: false,

                message: 'WhatsApp belum terhubung.'

            });

        }


        /*
        |--------------------------------------------------------------------------
        | FORMAT NOMOR
        |--------------------------------------------------------------------------
        */

        let number = String(phone)
            .replace(/\D/g, '');


        if (number.startsWith('0')) {

            number = '62' + number.substring(1);

        }


        /*
        |--------------------------------------------------------------------------
        | JID
        |--------------------------------------------------------------------------
        */

        const jid = `${number}@s.whatsapp.net`;


        /*
        |--------------------------------------------------------------------------
        | CEK NOMOR TERDAFTAR
        |--------------------------------------------------------------------------
        */

        const [result] = await sock.onWhatsApp(number);


        if (!result || !result.exists) {

            return res.status(404).json({

                success: false,

                message: 'Nomor WhatsApp tidak ditemukan.'

            });

        }


        /*
        |--------------------------------------------------------------------------
        | KIRIM PESAN
        |--------------------------------------------------------------------------
        */

        await sock.sendMessage(jid, {

            text: message

        });


        return res.json({

            success: true,

            message: 'Pesan WhatsApp berhasil dikirim.'

        });


    } catch (error) {

        console.error('');
        console.error('Gagal mengirim WhatsApp:');
        console.error(error);
        console.error('');

        return res.status(500).json({

            success: false,

            message: 'Gagal mengirim pesan WhatsApp.',

            error: error.message

        });

    }

});


/*
|--------------------------------------------------------------------------
| SERVER
|--------------------------------------------------------------------------
*/

app.listen(
    PORT,
    '127.0.0.1',
    () => {

        console.log('');
        console.log('========================================');
        console.log('       WHATSAPP BRIDGE');
        console.log('========================================');
        console.log(
            `Server : http://127.0.0.1:${PORT}`
        );
        console.log(
            `Status : http://127.0.0.1:${PORT}/status`
        );
        console.log(
            `Send   : http://127.0.0.1:${PORT}/send-message`
        );
        console.log('========================================');
        console.log('');

        startWhatsApp();

    }
);