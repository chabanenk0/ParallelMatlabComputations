function wwwexec(url,command,varargin);
% функция запуска рассчетов, данные для которого необходимо скачать по
% указанному урл и записать туда же (по фтп).
global server_data_url;
if (isempty(server_data_url))
    server_data_url='ftp://prognoz.sys-admin.dp.ua/pub/';
end
url=[server_data_url url];
lastslash=strfind(url,'/');
lastslash=lastslash(length(lastslash));
filename=url(lastslash+1:length(url));
url_out=url(1:lastslash);
folder=strrep(filename,'.rar','');


files=wwwgetfile(url);

params=command;
infile1='';
if (length(varargin)>0)
    if (ischar(varargin{1}))
        params=sprintf('%s(%c%s%c',params ,39, varargin{1},39);
    else
        if (isnumeric(varargin{1}))
            params=[params '(' num2str(varargin{1})];
        end
    end
    infile1=strrep(varargin{1},'.txt','_');
    for i=2:length(varargin)
        if (ischar(varargin{i}))
            params=sprintf('%s,%c%s%c',params ,39, varargin{i},39);
        else
            if (isnumeric(varargin{i}))
                params=[params ',' num2str(varargin{i})];
            end
        end
    end
    params=[params ')'];
end
eval(params);
%t=1;
outfilename=strrep(filename,'.rar',['_' infile1 'done.rar']);
wwwputfile2(url_out,files,outfilename);

cd ('..');