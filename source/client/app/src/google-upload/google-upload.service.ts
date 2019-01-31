import { Injectable } from '@angular/core';
import { HttpClient, HttpRequest, HttpEventType, HttpResponse } from '@angular/common/http';
import { Subject } from 'rxjs/Subject';
import { Observable } from 'rxjs/Observable';

const url = 'http://uploader-api.local:8080/api/upload';

@Injectable()
export class GoogleUploadService {
    constructor(private http: HttpClient) {}

    public uploadFile(username : string, file: File) {

        const formData: FormData = new FormData();
        formData.append('file', file, file.name);
        formData.append('username', username);

        // create a http-post request and pass the form
        const request = new HttpRequest('POST', url, formData);

        // send the http-request and subscribe for progress-updates
        return this.http.request(request);
    }
}