class AddTipoToNote < ActiveRecord::Migration
  def change
    add_column :notes, :tipo, :string
  end
end
