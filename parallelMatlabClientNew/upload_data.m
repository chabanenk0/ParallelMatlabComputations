function dataid=upload_data(filesource,archivefilename,userid,descr,server)
% программа, отсылающая на сервер пакет данных для рассчета.
% Параметры:
% filesource - путь к файлам задания (по умолчанию *.*)
% userid- ид пользователя, который загружает данные (по умолчанию 1
% descr - текстовое описание пакета данных
% server - сервер, куда загружается. По умолчанию http://127.0.0.1:80/

global path_to_rar;
if (isempty(path_to_rar))
    path_to_rar='C:\Program Files\WinRAR\';
end

if (nargin<5)
    server='http://127.0.0.1:80/';
end
if (nargin<4)
    descr='no descr';
end
if (nargin<3)
    userid=1;
end
if (nargin<2)
    archivefilename='file.rar';
end

if (nargin<1)
    filesource='*.*';
end
%archivenewfiles(filesource,archivefilename);
system(['"' path_to_rar 'rar" a -ep ' archivefilename ' ' filesource ]);
dataid=sendnewfiles_data(server,archivefilename,userid,descr);
