import { Injectable } from '@angular/core';
import { HttpClient, HttpRequest, HttpEventType, HttpResponse } from '@angular/common/http';
import { Subject } from 'rxjs/Subject';
import { Observable } from 'rxjs/Observable';

const url = 'http://uploader-test.local:8080/api/upload';

@Injectable()
export class GoogleUploadService {
    constructor(private http: HttpClient) {}

  public uploadFile(file: File): { [key: string]: Observable<number> } {

      const formData: FormData = new FormData();
      formData.append('file', file, file.name);

      // create a http-post request and pass the form
      // tell it to report the upload progress
      const request = new HttpRequest('POST', url, formData);

      // create a new progress-subject for every file
      const progress = new Subject<number>();

      // send the http-request and subscribe for progress-updates
      this.http.request(request).subscribe(event => {
            console.log(event);
      });

    // return the map of progress.observables
    return {};
  }
}